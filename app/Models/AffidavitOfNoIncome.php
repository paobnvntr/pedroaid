<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffidavitOfNoIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'aoni_name',
        'aoni_age',
        'aoni_address',
        'certificate_of_indigency',
        'previous_employer_name',
        'previous_employer_contact',
        'business_name',
        'registration_number',
        'business_address',
        'business_period',
        'no_income_period'
    ];
}
