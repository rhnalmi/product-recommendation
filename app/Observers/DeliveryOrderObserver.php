<?php

namespace App\Observers;

use App\Models\DeliveryOrder;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\StockReservation;
use Illuminate\Support\Facades\DB;

class DeliveryOrderObserver
{
    /**
     * Handle the DeliveryOrder "updated" event.
     */
    public function updated(DeliveryOrder $deliveryOrder): void
    {
        // Cek jika status berubah menjadi 'delivered'
        if ($deliveryOrder->wasChanged('status') && $deliveryOrder->status === 'delivered') {
            DB::transaction(function () use ($deliveryOrder) {
                $items = $deliveryOrder->items; // Asumsi ada relasi 'items' di model DeliveryOrder

                foreach ($items as $item) {
                    // 1. Update Tabel Inventory (kurangi stok tersedia & reservasi)
                    $inventory = Inventory::where('product_id', $item->part_number)->first();
                    if ($inventory) {
                        $inventory->decrement('quantity_available', $item->quantity);
                        $inventory->decrement('quantity_reserved', $item->quantity);
                    }

                    // 2. Catat di Inventory Movements
                    InventoryMovement::create([
                        'inventory_movement_id' => 'MOV-OUT-' . uniqid(),
                        'product_id' => $item->part_number,
                        'movement_type' => 'OUT',
                        'quantity' => $item->quantity,
                        'movement_date' => now(),
                        'reference_type' => 'DELIVERY',
                        'reference_id' => $deliveryOrder->id,
                        'notes' => 'Pengiriman untuk Sales Order: ' . $deliveryOrder->sales_order_id,
                    ]);
                }

                // 3. Update status reservasi menjadi 'RELEASED'
                StockReservation::where('sales_order_id', $deliveryOrder->sales_order_id)
                    ->update(['status' => 'RELEASED']);
            });
        }
    }
}