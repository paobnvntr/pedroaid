<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'valid_id_front',
        'valid_id_back',
    ];
}
