<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZenotiInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zenoti_invoice_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('invoice_id')->nullable(); // Related to the invoice
            $table->string('invoice_number')->nullable();
            $table->string('receipt_number')->nullable();
            $table->uuid('appointment_group_id')->nullable();
            $table->boolean('lock')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->boolean('is_refund')->default(false);
            $table->timestamp('invoice_date')->nullable();
            $table->uuid('center_id')->nullable();
            $table->uuid('guest_id')->nullable();
            $table->uuid('invoice_item_id')->nullable();
            $table->uuid('invoice_items_invoice_item_id')->nullable();
            $table->string('invoice_items_name')->nullable();
            $table->string('invoice_items_type')->nullable();
            $table->string('invoice_items_code')->nullable();
            $table->string('invoice_items_price_currency_id')->nullable();
            $table->string('invoice_items_price_sales')->nullable();
            $table->string('invoice_items_price_tax')->nullable();
            $table->string('invoice_items_price_final')->nullable();
            $table->string('invoice_items_price_discount')->nullable();
            $table->string('invoice_items_quantity')->nullable();
            $table->uuid('invoice_items_sale_by_id')->nullable();
            $table->string('invoice_items_therapist_name')->nullable();
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zenoti_invoice_item');
    }
}
