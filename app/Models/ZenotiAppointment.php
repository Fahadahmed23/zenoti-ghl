<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZenotiAppointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'zenoti_appointment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'appointment_group_id',
        'appointments_id',
        'invoice_item_id',
        'service_id',
        'start_time',
        'end_time',
        'start_time_in_center',
        'end_time_in_center',
        'service_duration_in_minutes',
        'has_add_ons',
        'is_add_on',
        'therapist_name',
        'therapist_id',
        'is_recurring',
        'show_in_calendar',
        'appointment_type',
        'therapist_request_type',
        'room_name',
        'equipment_name',
        'service_name'
    ];

    public function appointmentGroup()
    {
        return $this->belongsTo(ZenotiAppointmentGroup::class, 'appointment_group_id', 'appointment_group_id');
    }
}
