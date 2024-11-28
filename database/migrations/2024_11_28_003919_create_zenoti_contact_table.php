<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZenotiContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zenoti_contact', function (Blueprint $table) {

            $table->bigIncrements('id');
            
            // Zenoti fields mapped to SQL.
            $table->string('guest_id')->nullable();
            $table->string('guest_id2')->nullable();
            $table->string('code')->nullable();
            $table->string('center_id')->nullable();
            $table->string('center_name')->nullable();
            $table->timestamp('created_date')->nullable();

            // Personal info fields.
            $table->string('personal_info_user_name')->nullable();
            $table->string('personal_info_first_name')->nullable();
            $table->string('personal_info_last_name')->nullable();
            $table->string('personal_info_middle_name')->nullable();
            $table->string('personal_info_email')->nullable();
            $table->string('personal_info_mobile_phone')->nullable();
            $table->string('personal_info_work_phone')->nullable();
            $table->string('personal_info_home_phone')->nullable();
            $table->string('personal_info_gender')->nullable();
            $table->date('personal_info_date_of_birth')->nullable();
            $table->boolean('personal_info_is_minor')->default(false);
            $table->integer('personal_info_nationality_id')->nullable();
            $table->date('personal_info_anniversary_date')->nullable();
            $table->boolean('personal_info_lock_guest_custom_data')->default(false);
            $table->string('personal_info_pan')->nullable();
            $table->boolean('personal_info_dob_incomplete_year')->default(false);

            // Address info fields
            $table->string('address_info_address_1')->nullable();
            $table->string('address_info_address_2')->nullable();
            $table->string('address_info_city')->nullable();
            $table->integer('address_info_country_id')->nullable();
            $table->integer('address_info_state_id')->nullable();
            $table->string('address_info_state_other')->nullable();
            $table->string('address_info_zip_code')->nullable();

            $table->timestamps(); // Laravel's default created_at and updated_at.
            $table->softDeletes(); // Laravel's default deleted_at.

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zenoti_contact');
    }
}
