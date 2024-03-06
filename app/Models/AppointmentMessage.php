<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'email',
        'staff_name',
        'message',
        'created_at',
        'updated_at',
    ];
}
