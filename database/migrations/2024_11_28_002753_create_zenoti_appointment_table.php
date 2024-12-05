<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZenotiAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zenoti_appointment', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->uuid('appointment_group_id')->nullable();
            $table->uuid('appointments_id')->nullable();
            $table->string('invoice_item_id')->nullable();
            $table->uuid('service_id')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamp('start_time_in_center')->nullable();
            $table->timestamp('end_time_in_center')->nullable();
            $table->string('service_duration_in_minutes')->nullable();
            $table->boolean('has_add_ons')->default(false);
            $table->boolean('is_add_on')->default(false);
            $table->string('therapist_name')->nullable();
            $table->uuid('therapist_id')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->boolean('show_in_calendar')->default(false);
            $table->string('appointment_type')->nullable();
            $table->string('therapist_request_type')->nullable();
            $table->string('room_name')->nullable();
            $table->string('equipment_name')->nullable();
            $table->string('service_name')->nullable();
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
        Schema::dropIfExists('zenoti_appointment');
    }
}
