<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'email',
        'staff_name',
        'message',
        'created_at',
        'updated_at',
    ];
}
