<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZenotiContact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zenoti_contact'; // Define table name explicitly
    protected $primaryKey = 'id'; // Define primary key

    protected $fillable = [
        // Add all fillable columns for mass assignment
        'guest_id',
        'guest_id2',
        'code',
        'center_id',
        'center_name',
        'created_date',
        'personal_info_user_name',
        'personal_info_first_name',
        'personal_info_last_name',
        'personal_info_middle_name',
        'personal_info_email',
        'personal_info_mobile_phone',
        'personal_info_work_phone',
        'personal_info_home_phone',
        'personal_info_gender',
        'personal_info_date_of_birth',
        'personal_info_is_minor',
        'personal_info_nationality_id',
        'personal_info_anniversary_date',
        'personal_info_lock_guest_custom_data',
        'personal_info_pan',
        'personal_info_dob_incomplete_year',
        'address_info_address_1',
        'address_info_address_2',
        'address_info_city',
        'address_info_country_id',
        'address_info_state_id',
        'address_info_state_other',
        'address_info_zip_code',
    ];

    protected $dates = ['created_date', 'personal_info_date_of_birth', 'personal_info_anniversary_date'];

}
