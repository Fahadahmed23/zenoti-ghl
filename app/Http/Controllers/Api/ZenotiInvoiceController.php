<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiAppointmentGroup;
use App\Models\ZenotiAppointment;
use App\Models\ZenotiInvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;

class ZenotiInvoiceController extends Controller
{
   
    // Store or update the specified resource in storage.
    public function storeOrUpdateInvoiceData(Request $request) {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'data.invoice' => 'required|array',
            'data.invoice.invoice_items' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for incoming invoice', ['errors' => $validator->errors()]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Extract data from the request
        $invoice = $request->input('data.invoice');
        $guest = $invoice['guest'];
        $invoiceItems = $invoice['invoice_items'];

        // Prepare shared invoice-level data
        $sharedData = [
            'invoice_id' => $invoice['id'],
            'invoice_number' => $invoice['invoice_number'] ?? null,
            'receipt_number' => $invoice['receipt_number'] ?? null,
            'appointment_group_id' => $invoice['appointment_group_id'] ?? null,
            'lock' => $invoice['lock'] ?? false,
            'is_closed' => $invoice['is_closed'] ?? false,
            'is_refund' => $invoice['is_refund'] ?? false,
            'invoice_date' => isset($invoice['invoice_date']) ? Carbon::parse($invoice['invoice_date'])->format('Y-m-d H:i:s') : null,
            'center_id' => $invoice['center_id'] ?? null,
            'guest_id' => $guest['id'] ?? null,
        ];

        try {
      
            DB::transaction(function () use ($invoice, $invoiceItems, $sharedData) {
                // Fetch existing items for comparison
                $incomingInvoiceItemIds = collect($invoiceItems)->pluck('invoice_item_id')->toArray();

                // Soft delete invoice items not in the incoming list
                ZenotiInvoiceItem::where('invoice_id', $invoice['id'])
                    ->whereNotIn('invoice_items_invoice_item_id', $incomingInvoiceItemIds)
                    ->update(['deleted_at' => now()]);

                foreach ($invoiceItems as $item) {
                    ZenotiInvoiceItem::updateOrCreate(
                        ['invoice_items_invoice_item_id' => $item['invoice_item_id'] ?? null],
                        array_merge($sharedData, [
                            'invoice_item_id' => $item['id'] ?? null,
                            'invoice_items_name' => $item['name'] ?? null,
                            'invoice_items_type' => $item['type'] ?? null,
                            'invoice_items_code' => $item['code'] ?? null,
                            'invoice_items_price_currency_id' => $item['price']['currency_id'] ?? null,
                            'invoice_items_price_sales' => $item['price']['sales'] ?? 0,
                            'invoice_items_price_tax' => $item['price']['tax'] ?? 0,
                            'invoice_items_price_final' => $item['price']['final'] ?? 0,
                            'invoice_items_price_discount' => $item['price']['discount'] ?? 0,
                            'invoice_items_quantity' => $item['quantity'] ?? 1,
                            'invoice_items_sale_by_id' => $item['sale_by_id'] ?? null,
                            'invoice_items_therapist_name' => $item['therapist_name'] ?? null,
                        ])
                    );
                }
            });

            Log::info('Invoice processed successfully', ['invoice_id' => $invoice['id']]);

            return response()->json(['message' => 'Invoice data processed successfully'], 201);
        } catch (Exception $e) {
            Log::error('Failed to process invoice data', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to process data', 'error' => $e->getMessage()], 500);
        }
    }


}