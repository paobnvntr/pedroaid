<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'transaction_id',
        'transaction_type',
        'rating',
        'comment',
        'created_at',
        'updated_at',
    ];
}
