<?php

namespace App\Observers;

use App\Models\SalesOrder;
use App\Models\Inventory;
use App\Models\StockReservation;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesOrderObserver
{
    /**
     * Handle the SalesOrder "updated" event.
     * Ini akan dijalankan setiap kali Sales Order di-update.
     */
    public function updated(SalesOrder $salesOrder): void
    {
        // Cek jika status berubah menjadi 'confirmed'
        if ($salesOrder->wasChanged('status') && $salesOrder->status === 'confirmed') {
            $this->handleStockReservation($salesOrder);
        }

        // Cek jika status berubah menjadi 'rejected' atau 'cancelled'
        if ($salesOrder->wasChanged('status') && in_array($salesOrder->status, ['rejected', 'cancelled'])) {
            $this->handleReleaseReservation($salesOrder);
        }
    }

    /**
     * Membuat reservasi stok untuk setiap item dalam sales order.
     */
    protected function handleStockReservation(SalesOrder $salesOrder)
    {
        DB::transaction(function () use ($salesOrder) {
            $items = $salesOrder->items; // Asumsi ada relasi 'items' di model SalesOrder

            foreach ($items as $item) {
                $inventory = Inventory::where('product_id', $item->part_number)->first();

                if (!$inventory || ($inventory->quantity_available - $inventory->quantity_reserved) < $item->quantity) {
                    // Jika stok tidak cukup, batalkan transaksi dan lempar error
                    throw new Exception("Stok tidak mencukupi untuk produk: " . $item->part_number);
                }

                // Buat catatan reservasi
                StockReservation::create([
                    'part_number' => $item->part_number,
                    'sales_order_id' => $salesOrder->sales_order_id,
                    'reserved_quantity' => $item->quantity,
                    'reservation_date' => now(),
                    'status' => 'ACTIVE',
                ]);

                // Update kuantitas yang direservasi di tabel inventory
                $inventory->increment('quantity_reserved', $item->quantity);
            }
        });
    }

    /**
     * Melepaskan reservasi stok jika order dibatalkan.
     */
    protected function handleReleaseReservation(SalesOrder $salesOrder)
    {
        DB::transaction(function () use ($salesOrder) {
            $reservations = StockReservation::where('sales_order_id', $salesOrder->sales_order_id)
                ->where('status', 'ACTIVE')
                ->get();

            foreach ($reservations as $reservation) {
                $inventory = Inventory::where('product_id', $reservation->part_number)->first();
                if ($inventory) {
                    // Kurangi kuantitas yang direservasi
                    $inventory->decrement('quantity_reserved', $reservation->reserved_quantity);
                }
                // Ubah status reservasi menjadi tidak aktif
                $reservation->update(['status' => 'RELEASED']);
            }
        });
    }
}