<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeedOfSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'name_of_vendor',
        'vendor_civil_status',
        'vendor_address',
        'property_document',
        'property_price',
        'vendor_valid_id_front',
        'vendor_valid_id_back',
        'name_of_vendee',
        'vendee_valid_id_front',
        'vendee_valid_id_back',
        'name_of_witness',
        'witness_valid_id_front',
        'witness_valid_id_back',
    ];
}
