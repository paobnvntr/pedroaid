<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heir extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'surviving_heir',
        'spouse_of_heir',
    ];
}
