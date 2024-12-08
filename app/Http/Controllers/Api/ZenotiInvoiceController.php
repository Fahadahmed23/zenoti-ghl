<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiAppointmentGroup;
use App\Models\ZenotiAppointment;
use App\Models\ZenotiInvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ZenotiInvoiceController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $appointmentGroups = ZenotiAppointmentGroup::all();
        return response()->json($appointmentGroups);
    }

   
    // Store or update the specified resource in storage.
    public function storeOrUpdateInvoiceData(Request $request) {


        // Validate the request
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'data.invoice' => 'required|array',
            'data.invoice.invoice_items' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
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
    
            return response()->json(['message' => 'Invoice data processed successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process data', 'error' => $e->getMessage()], 500);
        }

        echo "<pre>";
        var_dump("Invoice",$sharedData);
        echo "</pre>";
        die;




    }

    // Display the specified resource.
    public function show($id)
    {
        $appointmentGroup = ZenotiAppointmentGroup::find($id);
        if (is_null($appointmentGroup)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        return response()->json($appointmentGroup);
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $appointmentGroup = ZenotiAppointmentGroup::find($id);
        if (is_null($appointmentGroup)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        $appointmentGroup->update($request->all());
        return response()->json($appointmentGroup);
    }

    // Remove the specified resource from storage.
    public function destroy(Request $request)
    {
        
        if ($request->input('event_type') === 'AppointmentGroup.Delete') {
            $appointmentGroupId = $request->input('data.appointment_group_id');
            
            // Check if the appointment group exists then delete it
            $appointmentGroup = ZenotiAppointmentGroup::where('appointment_group_id', $appointmentGroupId)->first();

            if (is_null($appointmentGroup)) {
                return response()->json(['message' => 'Resource not found'], 404);
            }

            // Update the appointment_group_status column to 'Lost'
            $appointmentGroup->appointment_group_status = 'Lost';
            $appointmentGroup->save();

            // Soft delete the appointment group
            $appointmentGroup->delete();
            return response()->json(['message' => 'Appointment group deleted successfully']);
        }
        
        return response()->json(['message' => 'Invalid event type'], 400);    
    }
}