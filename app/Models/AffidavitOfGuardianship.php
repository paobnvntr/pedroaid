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
        'civil_status',
        'address',
        'minor_name',
        'years_in_care',
        'valid_id_front',
        'valid_id_back',
    ];
}
