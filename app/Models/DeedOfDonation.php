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
        'donor_age',
        'donor_address',
        'donee_name',
        'donee_age',
        'donee_address',
    ];
}
