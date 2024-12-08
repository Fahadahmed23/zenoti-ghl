<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

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

        // Validate the request using Validator
        $validator = Validator::make($request->all(), [
            'data.id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed while creating/updating Zenoti contact', [
                'errors' => $validator->errors(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Start transaction
        DB::beginTransaction();
        try {
            $contactData = $request->input('data');
            $existingContact = ZenotiContact::where('guest_id', $contactData['id'])->first();

            if ($existingContact) {
                // Update existing contact
                $existingContact->update([
                    'guest_id' => $contactData['id'] ?? null,
                    'guest_id2' => $contactData['_id'] ?? null,
                    'code' => $contactData['code'] ?? null,
                    'center_id' => $contactData['center_id'] ?? null,
                    'center_name' => $contactData['center_name'] ?? null,
                    'created_date' => $contactData['created_date'] ?? null,
                    'personal_info_user_name' => $contactData['personal_info']['user_name'] ?? null,
                    'personal_info_first_name' => $contactData['personal_info']['first_name'] ?? null,
                    'personal_info_last_name' => $contactData['personal_info']['last_name'] ?? null,
                    'personal_info_middle_name' => $contactData['personal_info']['middle_name'] ?? null,
                    'personal_info_email' => $contactData['personal_info']['email'] ?? null,
                    'personal_info_mobile_phone' => $contactData['personal_info']['mobile_phone'] ?? null,
                    'personal_info_work_phone' => $contactData['personal_info']['work_phone'] ?? null,
                    'personal_info_home_phone' => $contactData['personal_info']['home_phone'] ?? null,
                    'personal_info_gender' => $contactData['personal_info']['gender'] ?? null,
                    'personal_info_date_of_birth' => $contactData['personal_info']['date_of_birth'] ?? null,
                    'personal_info_is_minor' => $contactData['personal_info']['is_minor'] ?? false,
                    'personal_info_nationality_id' => $contactData['personal_info']['nationality_id'] ?? null,
                    'personal_info_anniversary_date' => $contactData['personal_info']['anniversary_date'] ?? null,
                    'personal_info_lock_guest_custom_data' => $contactData['personal_info']['lock_guest_custom_data'] ?? false,
                    'personal_info_pan' => $contactData['personal_info']['pan'] ?? null,
                    'personal_info_dob_incomplete_year' => $contactData['personal_info']['dob_incomplete_year'] ?? false,
                    'address_info_address_1' => $contactData['address_info']['address_1'] ?? null,
                    'address_info_address_2' => $contactData['address_info']['address_2'] ?? null,
                    'address_info_city' => $contactData['address_info']['city'] ?? null,
                    'address_info_country_id' => $contactData['address_info']['country_id'] ?? null,
                    'address_info_state_id' => $contactData['address_info']['state_id'] ?? null,
                    'address_info_state_other' => $contactData['address_info']['state_other'] ?? null,
                    'address_info_zip_code' => $contactData['address_info']['zip_code'] ?? null,
                ]);
    
                Log::info('Zenoti contact updated successfully', [
                    'guest_id' => $contactData['id'],
                ]);
    
                DB::commit();
                return response()->json(['message' => 'Contact updated successfully'], 200);
            }
    
            // Create new contact
            $contact = new ZenotiContact();
            $contact->guest_id = $contactData['id'] ?? null;
            $contact->guest_id2 = $contactData['_id'] ?? null;
            $contact->code = $contactData['code'] ?? null;
            $contact->center_id = $contactData['center_id'] ?? null;
            $contact->center_name = $contactData['center_name'] ?? null;
            $contact->created_date = $contactData['created_date'] ?? null;
            $contact->personal_info_user_name = $contactData['personal_info']['user_name'] ?? null;
            $contact->personal_info_first_name = $contactData['personal_info']['first_name'] ?? null;
            $contact->personal_info_last_name = $contactData['personal_info']['last_name'] ?? null;
            $contact->personal_info_middle_name = $contactData['personal_info']['middle_name'] ?? null;
            $contact->personal_info_email = $contactData['personal_info']['email'] ?? null;
            $contact->personal_info_mobile_phone = $contactData['personal_info']['mobile_phone'] ?? null;
            $contact->personal_info_work_phone = $contactData['personal_info']['work_phone'] ?? null;
            $contact->personal_info_home_phone = $contactData['personal_info']['home_phone'] ?? null;
            $contact->personal_info_gender = $contactData['personal_info']['gender'] ?? null;
            $contact->personal_info_date_of_birth = $contactData['personal_info']['date_of_birth'] ?? null;
            $contact->personal_info_is_minor = $contactData['personal_info']['is_minor'] ?? false;
            $contact->personal_info_nationality_id = $contactData['personal_info']['nationality_id'] ?? null;
            $contact->personal_info_anniversary_date = $contactData['personal_info']['anniversary_date'] ?? null;
            $contact->personal_info_lock_guest_custom_data = $contactData['personal_info']['lock_guest_custom_data'] ?? false;
            $contact->personal_info_pan = $contactData['personal_info']['pan'] ?? null;
            $contact->personal_info_dob_incomplete_year = $contactData['personal_info']['dob_incomplete_year'] ?? false;
            $contact->address_info_address_1 = $contactData['address_info']['address_1'] ?? null;
            $contact->address_info_address_2 = $contactData['address_info']['address_2'] ?? null;
            $contact->address_info_city = $contactData['address_info']['city'] ?? null;
            $contact->address_info_country_id = $contactData['address_info']['country_id'] ?? null;
            $contact->address_info_state_id = $contactData['address_info']['state_id'] ?? null;
            $contact->address_info_state_other = $contactData['address_info']['state_other'] ?? null;
            $contact->address_info_zip_code = $contactData['address_info']['zip_code'] ?? null;
            $contact->save();
    
            Log::info('Zenoti contact created successfully', [
                'guest_id' => $contactData['id'],
            ]);
    
            DB::commit();
            return response()->json(['message' => 'Contact created successfully'], 201);
        
        
        } 
        catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to create/update Zenoti contact', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'An error occurred while processing the contact',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

       
}
