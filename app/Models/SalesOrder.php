<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    // TAMBAHKAN FUNGSI INI
    /**
     * Mendapatkan data customer (OutletDealer) yang terkait dengan Sales Order.
     */
    public function customer()
    {
        // Relasi ke model OutletDealer, melalui foreign key 'customer_id'
        // yang terhubung dengan primary key 'outlet_code' di tabel outlet_dealers.
        return $this->belongsTo(OutletDealer::class, 'customer_id', 'outlet_code');
    }
}