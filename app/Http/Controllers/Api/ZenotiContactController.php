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
                'data.id' => 'required|string|max:255'
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
            ]);
            return response()->json(['message' => 'Contact updated successfully'], 200);
        } 
        else {
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
            $contact->save();
            return response()->json(['message' => 'Contact created successfully'], 201);
        }
    }

       
}
