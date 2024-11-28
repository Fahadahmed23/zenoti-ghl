<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZenotiAppointmentGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zenoti_appointment_group', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('appointment_group_status')->nullable();
            $table->timestamp('event_timestamp')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('invoice_number_prefix')->nullable();
            $table->uuid('appointment_group_id')->nullable();
            $table->uuid('organization_id')->nullable();
            $table->uuid('center_id')->nullable();
            $table->string('center_name')->nullable();
            $table->uuid('guest_id')->nullable();
            $table->timestamps(); // Adds 'created_at' and 'updated_at'
            $table->softDeletes(); // Adds 'deleted_at'

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zenoti_appointment_group');
    }
}
