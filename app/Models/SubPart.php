<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubPart extends Model
{
    protected $table = 'sub_parts';
    protected $primaryKey = 'sub_part_number'; // Your primary key column name
    public $incrementing = false; // Important if your PK is not auto-incrementing integer
    protected $keyType = 'string'; // Important if your PK is a string

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'part_number',      // Foreign key to MasterPart
        'sub_part_number',  // The primary key for SubPart itself
        'sub_part_name',
        'price',
    ];

    public function masterPart(): BelongsTo
    {
        return $this->belongsTo(MasterPart::class, 'part_number', 'part_number');
    }
}