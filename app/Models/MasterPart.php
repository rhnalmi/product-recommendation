<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterPart extends Model
{
    protected $table = 'master_part';
    protected $primaryKey = 'part_number';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'part_number',
        'part_name',
        'part_price',
    ];

    public function subParts(): HasMany
    {
        return $this->hasMany(SubPart::class, 'part_number', 'part_number');
    }
}
