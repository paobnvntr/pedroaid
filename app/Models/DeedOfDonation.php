<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeedOfDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'donor_name',
        'donor_civil_status',
        'donor_address',
        'donor_valid_id_front',
        'donor_valid_id_back',
        'donee_name',
        'donee_civil_status',
        'donee_address',
        'donee_valid_id_front',
        'donee_valid_id_back',
        'property_description',
    ];
}
