<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZenotiAppointmentGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_group_status',
        'event_timestamp',
        'invoice_id',
        'invoice_number',
        'invoice_number_prefix',
        'appointment_group_id',
        'organization_id',
        'center_id',
        'center_name',
        'guest_id'
    ];

    public function appointments()
    {
        return $this->hasMany(ZenotiAppointment::class, 'appointment_group_id', 'appointment_group_id');
    }
}
