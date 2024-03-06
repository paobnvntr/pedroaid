<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentRequest extends Model
{
    use HasFactory;

    public static function generateUniqueDocumentRequestID()
    {
        do {
            $documentRequestId = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));
        } while (DocumentRequest::where('documentRequest_id', $documentRequestId)->exists());

        return $documentRequestId;
    }

    protected $fillable = [
        'documentRequest_id',
        'document_type',
        'name',
        'address',
        'email',
        'cellphone_number',
        'documentRequest_status',
    ];
}
