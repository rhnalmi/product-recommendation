<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletDealer extends Model
{
    use HasFactory;

    // Beri tahu model nama tabel yang benar
    protected $table = 'outlet_dealers';

    // Beri tahu model apa primary key-nya
    protected $primaryKey = 'outlet_code';

    // Beri tahu model bahwa primary key bukan auto-increment
    public $incrementing = false;

    // Beri tahu model bahwa tipe primary key adalah string
    protected $keyType = 'string';
}