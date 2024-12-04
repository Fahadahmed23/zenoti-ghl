<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiContact;
use Illuminate\Http\Request;

class ZenotiContactControllerBkp extends Controller
{
    /**
     * Fetch all Zenoti contacts.
     */
    public function index()
    {
        $contacts = ZenotiContact::all();
        return response()->json($contacts);
    }

    /**
     * Create a new Zenoti contact.
     */
    public function store(Request $request)
    {

        // echo 'hahaha';
        // die;
        // $validatedData = $request->validate([
        //     'guest_id' => 'nullable|string|max:255',
        //     'personal_info_first_name' => 'nullable|string|max:255',
        //     'personal_info_last_name' => 'nullable|string|max:255',
        //     'personal_info_email' => 'nullable|email|max:255',
        //     'personal_info_mobile_phone' => 'nullable|string|max:255',
        // ]);
        $validatedData = $request->validate([
            'data.id' => 'required|string|max:255',
            'data._id' => 'required|integer',
            'data.code' => 'required|string|max:255',
            'data.center_id' => 'required|string|max:255',
            'data.center_name' => 'required|string|max:255',
            'data.created_date' => 'required|date',
            'data.personal_info.user_name' => 'nullable|string|max:255',
            'data.personal_info.first_name' => 'required|string|max:255',
            'data.personal_info.last_name' => 'required|string|max:255',
            'data.personal_info.middle_name' => 'nullable|string|max:255',
            'data.personal_info.email' => 'nullable|email|max:255',
            'data.personal_info.mobile_phone' => 'nullable|string|max:255',
            'data.personal_info.work_phone' => 'nullable|string|max:255',
            'data.personal_info.home_phone' => 'nullable|string|max:255',
            'data.personal_info.gender' => 'required|integer',
            'data.personal_info.date_of_birth' => 'nullable|date',
            'data.personal_info.is_minor' => 'required|boolean',
            'data.personal_info.nationality_id' => 'required|integer',
            'data.personal_info.anniversary_date' => 'nullable|date',
            'data.personal_info.lock_guest_custom_data' => 'required|boolean',
            'data.personal_info.pan' => 'nullable|string|max:255',
            'data.personal_info.dob_incomplete_year' => 'nullable|integer',
            'data.address_info.address_1' => 'nullable|string|max:255',
            'data.address_info.address_2' => 'nullable|string|max:255',
            'data.address_info.city' => 'nullable|string|max:255',
            'data.address_info.country_id' => 'required|integer',
            'data.address_info.state_id' => 'required|integer',
            'data.address_info.state_other' => 'nullable|string|max:255',
            'data.address_info.zip_code' => 'required|string|max:255',
            'data.preferences.receive_transactional_email' => 'required|boolean',
            'data.preferences.receive_transactional_sms' => 'required|boolean',
            'data.preferences.receive_marketing_email' => 'required|boolean',
            'data.preferences.receive_marketing_sms' => 'required|boolean',
            'data.preferences.recieve_lp_stmt' => 'required|boolean',
            'data.preferences.opt_in_for_loyalty_program' => 'required|boolean',
            'data.preferences.transactional_sms_optin' => 'required|integer',
            'data.preferences.transactional_email_optin' => 'required|integer',
            'data.preferences.marketing_sms_optin' => 'required|integer',
            'data.preferences.marketing_email_optin' => 'required|integer',
            'data.preferences.transactional_whatsapp_optin' => 'required|integer',
            'data.preferences.receive_transactional_whatsapp' => 'required|boolean',
        ]);

        // var dump request data
        echo "<pre>";
        var_dump($request->all());
        echo "</pre>";
        die;
     
        //$contact = ZenotiContact::create($validatedData);

        return response()->json(['message' => 'Contact created successfully', 'data' => $contact], 201);
    }
}
