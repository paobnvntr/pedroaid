<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffidavitOfGuardianship extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'guardian_name',
        'guardian_age',
        'guardian_address',
        'guardian_occupation',
        'guardian_brgy_clearance',
        'guardian_relationship',
        'minor_name',
        'minor_age',
        'minor_address',
        'minor_relationship',
    ];
}
