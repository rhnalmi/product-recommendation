<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Sangat disarankan untuk mengubah nama class menjadi "DeliveryOrder"
class DeliveryOrder extends Model
{
    protected $table = 'delivery_orders';

    // TAMBAHKAN FUNGSI INI
    /**
     * Mendapatkan data SalesOrder yang terkait dengan Delivery Order.
     */
    public function salesOrder()
    {
        // Relasi ke model SalesOrder, melalui foreign key 'sales_order_id'
        // yang terhubung dengan primary key 'sales_order_id' di tabel sales_orders.
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'sales_order_id');
    }
}