<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'type', 
        'user', 
        'subject', 
        'message',
        'created_at',
        'updated_at',
    ];
}
