<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    public static function generateUniqueAppointmentID()
    {
        do {
            $appointmentId = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));
        } while (Appointment::where('appointment_id', $appointmentId)->exists());

        return $appointmentId;
    }

    protected $fillable = [
        'appointment_id',
        'name',
        'address',
        'cellphone_number',
        'email',
        'appointment_date',
        'appointment_time',
        'appointment_status',
        'date_finished',
        'notes',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
