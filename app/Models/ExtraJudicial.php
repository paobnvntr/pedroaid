<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraJudicial extends Model
{
    use HasFactory;

    protected $fillable = [
        'documentRequest_id',
        'title_of_property',
        'title_holder',
        'surviving_spouse',
        'spouse_valid_id_front',
        'spouse_valid_id_back',
    ];
}
