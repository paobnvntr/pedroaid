<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffidavitOfLoss extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'aol_name',
        'aol_age',
        'aol_address',
        'valid_id_front',
        'valid_id_back',
        'cedula',
    ];
}