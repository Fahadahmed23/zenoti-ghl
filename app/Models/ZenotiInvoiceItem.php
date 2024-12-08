<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZenotiInvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zenoti_invoice_item'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'invoice_id',
        'invoice_number',
        'receipt_number',
        'appointment_group_id',
        'lock',
        'is_closed',
        'is_refund',
        'invoice_date',
        'center_id',
        'guest_id',
        'invoice_items_invoice_item_id',
        'invoice_item_id',
        'invoice_items_name',
        'invoice_items_type',
        'invoice_items_code',
        'invoice_items_price_currency_id',
        'invoice_items_price_sales',
        'invoice_items_price_tax',
        'invoice_items_price_final',
        'invoice_items_price_discount',
        'invoice_items_quantity',
        'invoice_items_sale_by_id',
        'invoice_items_therapist_name',
    ];


}
