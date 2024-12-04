<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiContact;
use Illuminate\Http\Request;

class ZenotiContactController extends Controller
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

       
        try {
            $validatedData = $request->validate([
                'data.id' => 'required|string|max:255',
                // 'data._id' => 'required|integer',
                // 'data.code' => 'required|string|max:255',
                // 'data.center_id' => 'required|string|max:255',
                // 'data.center_name' => 'required|string|max:255',
                // 'data.created_date' => 'required|date',

                // Personal Info
                // 'data.personal_info.user_name' => 'nullable|string|max:255',
                // 'data.personal_info.first_name' => 'required|string|max:255',
                // 'data.personal_info.last_name' => 'required|string|max:255',
                // 'data.personal_info.middle_name' => 'nullable|string|max:255',
                // 'data.personal_info.email' => 'nullable|email|max:255',
                // 'data.personal_info.mobile_phone' => 'nullable|string|max:255',
                // 'data.personal_info.work_phone' => 'nullable|string|max:255',
                // 'data.personal_info.home_phone' => 'nullable|string|max:255',
                // 'data.personal_info.gender' => 'required|integer',
                // 'data.personal_info.date_of_birth' => 'nullable|date',
                'data.personal_info.is_minor' => 'required|boolean',
                // 'data.personal_info.nationality_id' => 'required|integer',
                // 'data.personal_info.anniversary_date' => 'nullable|date',
                // 'data.personal_info.lock_guest_custom_data' => 'required|boolean',
                // 'data.personal_info.pan' => 'nullable|string|max:255',
                // 'data.personal_info.dob_incomplete_year' => 'nullable|integer',

                // Address Info
                // 'data.address_info.address_1' => 'nullable|string|max:255',
                // 'data.address_info.address_2' => 'nullable|string|max:255',
                // 'data.address_info.city' => 'nullable|string|max:255',
                // 'data.address_info.country_id' => 'required|integer',
                // 'data.address_info.state_id' => 'required|integer',
                // 'data.address_info.state_other' => 'nullable|string|max:255',
                // 'data.address_info.zip_code' => 'required|string|max:255',

                // Preferences
                // 'data.preferences.receive_transactional_email' => 'required|boolean',
                // 'data.preferences.receive_transactional_sms' => 'required|boolean',
                // 'data.preferences.receive_marketing_email' => 'required|boolean',
                // 'data.preferences.receive_marketing_sms' => 'required|boolean',
                // 'data.preferences.recieve_lp_stmt' => 'required|boolean',
                // 'data.preferences.opt_in_for_loyalty_program' => 'required|boolean',
                // 'data.preferences.transactional_sms_optin' => 'required|integer',
                // 'data.preferences.transactional_email_optin' => 'required|integer',
                // 'data.preferences.marketing_sms_optin' => 'required|integer',
                // 'data.preferences.marketing_email_optin' => 'required|integer',
                // 'data.preferences.transactional_whatsapp_optin' => 'required|integer',
                // 'data.preferences.receive_transactional_whatsapp' => 'required|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $contact = new ZenotiContact();
        $existingContact = $request->has('data.id') ? ZenotiContact::where('guest_id', $request->input('data.id'))->first() : null;

        if ($existingContact) {
            $existingContact->update([
            'guest_id' => $request->input('data.id') ?? null,
            'guest_id2' => $request->input('data._id') ?? null,
            'code' => $request->input('data.code') ?? null,
            'center_id' => $request->input('data.center_id') ?? null,
            'center_name' => $request->input('data.center_name') ?? null,
            'created_date' => $request->input('data.created_date') ?? null,
            'personal_info_user_name' => $request->input('data.personal_info.user_name') ?? null,
            'personal_info_first_name' => $request->input('data.personal_info.first_name') ?? null,
            'personal_info_last_name' => $request->input('data.personal_info.last_name') ?? null,
            'personal_info_middle_name' => $request->input('data.personal_info.middle_name') ?? null,
            'personal_info_email' => $request->input('data.personal_info.email') ?? null,
            'personal_info_mobile_phone' => $request->input('data.personal_info.mobile_phone') ?? null,
            'personal_info_work_phone' => $request->input('data.personal_info.work_phone') ?? null,
            'personal_info_home_phone' => $request->input('data.personal_info.home_phone') ?? null,
            'personal_info_gender' => $request->input('data.personal_info.gender') ?? null,
            'personal_info_date_of_birth' => $request->input('data.personal_info.date_of_birth') ?? null,
            'personal_info_is_minor' => $request->input('data.personal_info.is_minor') ?? false,
            'personal_info_nationality_id' => $request->input('data.personal_info.nationality_id') ?? null,
            'personal_info_anniversary_date' => $request->input('data.personal_info.anniversary_date') ?? null,
            'personal_info_lock_guest_custom_data' => $request->input('data.personal_info.lock_guest_custom_data') ?? false,
            'personal_info_pan' => $request->input('data.personal_info.pan') ?? null,
            'personal_info_dob_incomplete_year' => $request->input('data.personal_info.dob_incomplete_year') ?? false,
            'address_info_address_1' => $request->input('data.address_info.address_1') ?? null,
            'address_info_address_2' => $request->input('data.address_info.address_2') ?? null,
            'address_info_city' => $request->input('data.address_info.city') ?? null,
            'address_info_country_id' => $request->input('data.address_info.country_id') ?? null,
            'address_info_state_id' => $request->input('data.address_info.state_id') ?? null,
            'address_info_state_other' => $request->input('data.address_info.state_other') ?? null,
            'address_info_zip_code' => $request->input('data.address_info.zip_code') ?? null,
            // 'preferences_receive_transactional_email' => $request->input('data.preferences.receive_transactional_email') ?? null,
            // 'preferences_receive_transactional_sms' => $request->input('data.preferences.receive_transactional_sms') ?? null,
            // 'preferences_receive_marketing_email' => $request->input('data.preferences.receive_marketing_email') ?? null,
            // 'preferences_receive_marketing_sms' => $request->input('data.preferences.receive_marketing_sms') ?? null,
            // 'preferences_recieve_lp_stmt' => $request->input('data.preferences.recieve_lp_stmt') ?? null,
            // 'preferences_opt_in_for_loyalty_program' => $request->input('data.preferences.opt_in_for_loyalty_program') ?? null,
            // 'preferences_transactional_sms_optin' => $request->input('data.preferences.transactional_sms_optin') ?? null,
            // 'preferences_transactional_email_optin' => $request->input('data.preferences.transactional_email_optin') ?? null,
            // 'preferences_marketing_sms_optin' => $request->input('data.preferences.marketing_sms_optin') ?? null,
            // 'preferences_marketing_email_optin' => $request->input('data.preferences.marketing_email_optin') ?? null,
            // 'preferences_transactional_whatsapp_optin' => $request->input('data.preferences.transactional_whatsapp_optin') ?? null,
            // 'preferences_receive_transactional_whatsapp' => $request->input('data.preferences.receive_transactional_whatsapp') ?? null,
            ]);
        } else {
            $contact->guest_id = $request->input('data.id') ?? null;
            $contact->guest_id2 = $request->input('data._id') ?? null;
            $contact->code = $request->input('data.code') ?? null;
            $contact->center_id = $request->input('data.center_id') ?? null;
            $contact->center_name = $request->input('data.center_name') ?? null;
            $contact->created_date = $request->input('data.created_date') ?? null;
            $contact->personal_info_user_name = $request->input('data.personal_info.user_name') ?? null;
            $contact->personal_info_first_name = $request->input('data.personal_info.first_name') ?? null;
            $contact->personal_info_last_name = $request->input('data.personal_info.last_name') ?? null;
            $contact->personal_info_middle_name = $request->input('data.personal_info.middle_name') ?? null;
            $contact->personal_info_email = $request->input('data.personal_info.email') ?? null;
            $contact->personal_info_mobile_phone = $request->input('data.personal_info.mobile_phone') ?? null;
            $contact->personal_info_work_phone = $request->input('data.personal_info.work_phone') ?? null;
            $contact->personal_info_home_phone = $request->input('data.personal_info.home_phone') ?? null;
            $contact->personal_info_gender = $request->input('data.personal_info.gender') ?? null;
            $contact->personal_info_date_of_birth = $request->input('data.personal_info.date_of_birth') ?? null;
            $contact->personal_info_is_minor = $request->input('data.personal_info.is_minor') ?? false;
            $contact->personal_info_nationality_id = $request->input('data.personal_info.nationality_id') ?? null;
            $contact->personal_info_anniversary_date = $request->input('data.personal_info.anniversary_date') ?? null;
            $contact->personal_info_lock_guest_custom_data = $request->input('data.personal_info.lock_guest_custom_data') ?? false;
            $contact->personal_info_pan = $request->input('data.personal_info.pan') ?? null;
            $contact->personal_info_dob_incomplete_year = $request->input('data.personal_info.dob_incomplete_year') ?? false;
            $contact->address_info_address_1 = $request->input('data.address_info.address_1') ?? null;
            $contact->address_info_address_2 = $request->input('data.address_info.address_2') ?? null;
            $contact->address_info_city = $request->input('data.address_info.city') ?? null;
            $contact->address_info_country_id = $request->input('data.address_info.country_id') ?? null;
            $contact->address_info_state_id = $request->input('data.address_info.state_id') ?? null;
            $contact->address_info_state_other = $request->input('data.address_info.state_other') ?? null;
            $contact->address_info_zip_code = $request->input('data.address_info.zip_code') ?? null;
            // $contact->preferences_receive_transactional_email = $request->input('data.preferences.receive_transactional_email') ?? null;
            // $contact->preferences_receive_transactional_sms = $request->input('data.preferences.receive_transactional_sms') ?? null;
            // $contact->preferences_receive_marketing_email = $request->input('data.preferences.receive_marketing_email') ?? null;
            // $contact->preferences_receive_marketing_sms = $request->input('data.preferences.receive_marketing_sms') ?? null;
            // $contact->preferences_recieve_lp_stmt = $request->input('data.preferences.recieve_lp_stmt') ?? null;
            // $contact->preferences_opt_in_for_loyalty_program = $request->input('data.preferences.opt_in_for_loyalty_program') ?? null;
            // $contact->preferences_transactional_sms_optin = $request->input('data.preferences.transactional_sms_optin') ?? null;
            // $contact->preferences_transactional_email_optin = $request->input('data.preferences.transactional_email_optin') ?? null;
            // $contact->preferences_marketing_sms_optin = $request->input('data.preferences.marketing_sms_optin') ?? null;
            // $contact->preferences_marketing_email_optin = $request->input('data.preferences.marketing_email_optin') ?? null;
            // $contact->preferences_transactional_whatsapp_optin = $request->input('data.preferences.transactional_whatsapp_optin') ?? null;
            // $contact->preferences_receive_transactional_whatsapp = $request->input('data.preferences.receive_transactional_whatsapp') ?? null;
            $contact->save();
        }

        return response()->json(['message' => 'Contact created successfully'], 201);
    }


    

     
     
        
}
