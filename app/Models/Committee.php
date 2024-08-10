<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'chairman',
        'vice_chairman',
        'member_1',
        'member_2',
        'member_3',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
