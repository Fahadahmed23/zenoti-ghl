<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiAppointmentGroup;
use App\Models\ZenotiAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class ZenotiAppointmentGroupController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $appointmentGroups = ZenotiAppointmentGroup::all();
        return response()->json($appointmentGroups);
    }

    // Store or update a resource in storage.
    public function storeOrUpdate(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'data.appointment_group_id' => 'required|uuid',
        ], [
            'data.appointment_group_id.required' => 'The appointment group ID is required.',
            'data.appointment_group_id.uuid' => 'The appointment group ID must be a valid UUID.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed for appointment group', [
                'errors' => $validator->errors(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Parse event timestamp
            $eventTimestamp = $request->input('event_timestamp') 
            ? Carbon::parse($request->input('event_timestamp'))->format('Y-m-d H:i:s') 
            : null;

            // Update or create appointment group
            $appointmentGroup = ZenotiAppointmentGroup::updateOrCreate(
                ['appointment_group_id' => $request->input('data.appointment_group_id')],
                [   
                    'appointment_group_status' => $request->input('data.status') ?? 'Appointment Scheduled',
                    'invoice_id' => $request->input('data.invoice_id') ?? null,
                    'invoice_number' => $request->input('data.invoice_number') ?? null,
                    'invoice_number_prefix' => $request->input('data.invoice_number_prefix') ?? null,
                    'organization_id' => $request->input('data.organization_id') ?? null,
                    'center_id' => $request->input('data.center_id') ?? null,
                    'center_name' => $request->input('data.center_Name') ?? null,
                    'guest_id' => $request->input('data.guest.id') ?? null,
                    'event_timestamp' => $eventTimestamp,
                ]
            );

            Log::info('Appointment group processed successfully', [
                'appointment_group_id' => $appointmentGroup->appointment_group_id,
            ]);
            
            // Process appointments
            $appointments = $request->input('data.appointments') ?? [];
    
            if (!empty($appointments)) { 
                
                 // Get existing appointment IDs for the group
                $existingAppointmentIds = ZenotiAppointment::where('appointment_group_id', $appointmentGroup->appointment_group_id)
                ->pluck('appointments_id')
                ->toArray();
    
                $incomingAppointmentIds = collect($appointments)->pluck('id')->toArray();
    
                // Soft delete appointments not in the incoming list
                ZenotiAppointment::where('appointment_group_id', $appointmentGroup->appointment_group_id)
                    ->whereNotIn('appointments_id', $incomingAppointmentIds)
                    ->update(['deleted_at' => now()]);
                
                foreach ($appointments as $appointmentData) {
                    ZenotiAppointment::updateOrCreate(
                        ['appointments_id' => $appointmentData['id']],
                        [
                            'appointment_group_id' => $appointmentGroup->appointment_group_id,
                            'invoice_item_id' => $appointmentData['invoice_item_id'] ?? null,
                            'service_name' => $appointmentData['service_name'] ?? null,
                            'service_id' => $appointmentData['service_id'] ?? null,
                            'start_time' => Carbon::parse($appointmentData['start_time'])->format('Y-m-d H:i:s') ?? null,
                            'end_time' => Carbon::parse($appointmentData['end_time'])->format('Y-m-d H:i:s') ?? null,
                            'start_time_in_center' => Carbon::parse($appointmentData['start_time_in_center'])->format('Y-m-d H:i:s') ?? null,
                            'end_time_in_center' => Carbon::parse($appointmentData['end_time_in_center'])->format('Y-m-d H:i:s') ?? null,
                            'service_duration_in_minutes' => $appointmentData['service_duration_in_minutes'] ?? null,
                            'has_add_ons' => $appointmentData['has_add_ons'] ?? null,
                            'is_add_on' => $appointmentData['is_add_on'] ?? null,
                            'therapist_name' => $appointmentData['therapist_name'] ?? null,
                            'therapist_id' => $appointmentData['therapist_id'] ?? null,
                            'is_recurring' => $appointmentData['is_recurring'] ?? null,
                            'show_in_calendar' => $appointmentData['show_in_calendar'] ?? null,
                            'appointment_type' => $appointmentData['appointment_type'] ?? null,
                            'therapist_request_type' => $appointmentData['therapist_request_type'] ?? null,
                            'room_id' => $appointmentData['room_id'] ?? null,
                            'room_name' => $appointmentData['room_name'] ?? null,
                            'equipment_name' => $appointmentData['equipment_name'] ?? null,
                            'service_name' => $appointmentData['service_name'] ?? null,
                        ]
                    );
                }
                Log::info('Appointments processed successfully', [
                    'appointment_group_id' => $appointmentGroup->appointment_group_id,
                ]);
            }
            else {
    
                // Soft delete all appointments for the group if none are provided
                ZenotiAppointment::where('appointment_group_id', $appointmentGroup->appointment_group_id)
                ->update(['deleted_at' => now()]);

                Log::info('All appointments soft deleted for appointment group', [
                    'appointment_group_id' => $appointmentGroup->appointment_group_id,
                ]);
            }

            DB::commit();
          
            return response()->json(['message' => 'Appointment group and appointments processed successfully'], 201);

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Failed to process appointment group', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'An error occurred while processing the appointment group',
                'error' => $e->getMessage(),
            ], 500);
        }   
    }

    // Display the specified resource.
    public function show($id)
    {
        $appointmentGroup = ZenotiAppointmentGroup::find($id);
        if (is_null($appointmentGroup)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        return response()->json($appointmentGroup);
    }


    // Remove the specified resource from storage.
    public function destroy(Request $request)
    {

        if ($request->input('event_type') !== 'AppointmentGroup.Delete') {
            Log::warning('Invalid event type for destroy operation', [
                'event_type' => $request->input('event_type'),
                'request' => $request->all(),
            ]);
    
            return response()->json(['message' => 'Invalid event type'], 400);
        }
        
        $appointmentGroupId = $request->input('data.appointment_group_id');
        
        DB::beginTransaction();
        try {

            // Check if the appointment group exists then delete it
            $appointmentGroup = ZenotiAppointmentGroup::where('appointment_group_id', $appointmentGroupId)->first();

            if (is_null($appointmentGroup)) {

                Log::info('Attempt to delete non-existent appointment group', [
                    'appointment_group_id' => $appointmentGroupId,
                ]);
                return response()->json(['message' => 'Resource not found'], 404);
            }

            // Update the appointment_group_status column to 'Lost'
            $appointmentGroup->appointment_group_status = 'Lost';
            $appointmentGroup->save();

            // Soft delete the appointment group
            $appointmentGroup->delete();

            Log::info('Appointment group soft deleted successfully', [
                'appointment_group_id' => $appointmentGroupId,
            ]);

            DB::commit();

            return response()->json(['message' => 'Appointment group deleted successfully']);
        
        
        }
        catch(Exception $e){
            DB::rollBack();

            Log::error('Failed to delete appointment group', [
                'appointment_group_id' => $appointmentGroupId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'An error occurred while deleting the appointment group',
                'error' => $e->getMessage(),
            ], 500);

        } 
    }
}