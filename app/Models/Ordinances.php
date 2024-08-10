<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordinances extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee', 
        'ordinance_number', 
        'date_approved',
        'description',
        'ordinance_file',
        'view_count',
        'download_count',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
