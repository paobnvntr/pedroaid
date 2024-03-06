<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeedOfSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'name_identity_1',
        'name_identity_2',
        'details',
    ];
}
