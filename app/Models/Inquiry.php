<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Inquiry extends Model
{
    use HasFactory;

    public static function generateUniqueInquiryID()
    {
        do {
            $inquiryId = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));
        } while (Inquiry::where('inquiry_id', $inquiryId)->exists());

        return $inquiryId;
    }

    protected $fillable = [
        'inquiry_id',
        'name',
        'email',
        'inquiry',
        'status',
        'is_active'
    ];
}
