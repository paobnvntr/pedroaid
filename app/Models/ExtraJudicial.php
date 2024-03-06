<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraJudicial extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'death_cert',
        'heirship',
        'inv_estate',
        'tax_clearance',
        'deed_extrajudicial',
    ];
}
