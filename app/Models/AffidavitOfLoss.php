<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffidavitOfLoss extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'name',
        'civil_status',
        'address',
        'item_lost',
        'reason_of_loss',
        'valid_id_front',
        'valid_id_back',
    ];
}