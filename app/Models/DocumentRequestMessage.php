<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequestMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'email',
        'staff_name',
        'message',
    ];
}
