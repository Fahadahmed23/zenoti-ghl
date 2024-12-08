<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiAppointmentGroup;
use App\Models\ZenotiAppointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ZenotiAppointmentGroupController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $appointmentGroups = ZenotiAppointmentGroup::all();
        return response()->json($appointmentGroups);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $appointmentGroup = ZenotiAppointmentGroup::create($request->all());
        return response()->json($appointmentGroup, 201);
    }

    // Store or update a resource in storage.
    public function storeOrUpdate(Request $request)
    {
 
        try {
            $validatedData = $request->validate([
                'data.appointment_group_id' => 'required|uuid'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $eventTimestamp = $request->input('event_timestamp') 
            ? Carbon::parse($request->input('event_timestamp'))->format('Y-m-d H:i:s') 
            : null;

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

        $appointments = $request->input('data.appointments') ?? [];

        if (!empty($appointments)) { 
            
            // Get current appointments IDs for the group
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
        }
        else {

            // Soft delete all appointments for the group if none are provided
            ZenotiAppointment::where('appointment_group_id', $appointmentGroup->appointment_group_id)
            ->update(['deleted_at' => now()]);
        }
      
        return response()->json(['message' => 'Appointment group and appointments processed successfully'], 201);
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

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $appointmentGroup = ZenotiAppointmentGroup::find($id);
        if (is_null($appointmentGroup)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        $appointmentGroup->update($request->all());
        return response()->json($appointmentGroup);
    }

    // Remove the specified resource from storage.
    public function destroy(Request $request)
    {
        
        if ($request->input('event_type') === 'AppointmentGroup.Delete') {
            $appointmentGroupId = $request->input('data.appointment_group_id');
            
            // Check if the appointment group exists then delete it
            $appointmentGroup = ZenotiAppointmentGroup::where('appointment_group_id', $appointmentGroupId)->first();

            if (is_null($appointmentGroup)) {
                return response()->json(['message' => 'Resource not found'], 404);
            }

            // Update the appointment_group_status column to 'Lost'
            $appointmentGroup->appointment_group_status = 'Lost';
            $appointmentGroup->save();

            // Soft delete the appointment group
            $appointmentGroup->delete();
            return response()->json(['message' => 'Appointment group deleted successfully']);
        }
        
        return response()->json(['message' => 'Invalid event type'], 400);    
    }
}