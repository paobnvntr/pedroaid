<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffidavitOfNoFixIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'aonfi_name',
        'aonfi_age',
        'aonfi_address',
        'source_income',
        'indigency',
    ];
}
