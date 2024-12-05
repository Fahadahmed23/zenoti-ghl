<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ZenotiAppointmentGroup;
use App\Models\ZenotiAppointment;
use Illuminate\Http\Request;

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

        $appointmentGroup = ZenotiAppointmentGroup::updateOrCreate(
            ['appointment_group_id' => $request->input('data.appointment_group_id')],
            [   
                'appointment_group_status' => $request->input('data.status') ?? 'Appointment Scheduled',
                'invoice_id' => $request->input('data.invoice_id') ?? null,
                'invoice_number' => $request->input('data.invoice_number') ?? null,
                'invoice_number_prefix' => $request->input('data.invoice_number_prefix') ?? null,
                'organization_id' => $request->input('data.organization_id') ?? null,
                'center_id' => $request->input('data.center_id') ?? null,
                'center_name' => $request->input('data.center_name') ?? null,
                'guest_id' => $request->input('data.guest.id') ?? null,
                'event_timestamp' => $request->input('event_timestamp') ?? null,
            ]
        );

        foreach ($request->input('data.appointments') as $appointmentData) {
            ZenotiAppointment::updateOrCreate(
                ['appointments_id' => $appointmentData['id']],
                [
                    'appointment_group_id' => $appointmentGroup->id,
                    'invoice_item_id' => $appointmentData['invoice_item_id'] ?? null,
                    'service_name' => $appointmentData['service_name'] ?? null,
                    'service_id' => $appointmentData['service_id'] ?? null,
                    'start_time' => $appointmentData['start_time'] ?? null,
                    'end_time' => $appointmentData['end_time'] ?? null,
                    'start_time_in_center' => $appointmentData['start_time_in_center'] ?? null,
                    'end_time_in_center' => $appointmentData['end_time_in_center'] ?? null,
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

        return response()->json(['message' => 'Appointment group created or updated successfully'], 201);
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
    public function destroy($id)
    {
        $appointmentGroup = ZenotiAppointmentGroup::find($id);
        if (is_null($appointmentGroup)) {
            return response()->json(['message' => 'Resource not found'], 404);
        }
        $appointmentGroup->delete();
        return response()->json(null, 204);
    }
}