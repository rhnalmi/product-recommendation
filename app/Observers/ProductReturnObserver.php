<?php

namespace App\Observers;

use App\Models\ProductReturn;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class ProductReturnObserver
{
    /**
     * Handle the ProductReturn "created" event.
     */
    public function created(ProductReturn $productReturn): void
    {
        DB::transaction(function () use ($productReturn) {
            $inventory = Inventory::where('product_id', $productReturn->part_number)->first();

            if ($inventory) {
                // Cek kondisi barang retur
                if ($productReturn->condition === 'GOOD') {
                    // Jika kondisi baik, tambahkan ke stok tersedia
                    $inventory->increment('quantity_available', $productReturn->quantity);
                } else {
                    // Jika kondisi rusak, tambahkan ke stok rusak
                    $inventory->increment('quantity_damaged', $productReturn->quantity);
                }
            }

            // Catat di Inventory Movements
            InventoryMovement::create([
                'inventory_movement_id' => 'MOV-IN-' . uniqid(),
                'product_id' => $productReturn->part_number,
                'movement_type' => 'IN',
                'quantity' => $productReturn->quantity,
                'movement_date' => now(),
                'reference_type' => 'RETURN',
                'reference_id' => $productReturn->id,
                'notes' => 'Retur dari Sales Order: ' . $productReturn->sales_order_id . ' (Kondisi: ' . $productReturn->condition . ')',
            ]);
        });
    }
}