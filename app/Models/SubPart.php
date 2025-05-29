<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubPart extends Model
{
    protected $table = 'sub_parts';
    protected $primaryKey = 'sub_part_number';
    public $incrementing = false;

    public function masterPart(): BelongsTo
    {
        return $this->belongsTo(MasterPart::class, 'part_number', 'part_number');
    }
}
