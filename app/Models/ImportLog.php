<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'file_checksum',
        'status',
        'total_rows',
        'processed_rows',
        'new_records',
        'updated_records',
        'error_message',
        'user_id',
    ];

    /**
     * Mendapatkan pengguna yang melakukan impor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
