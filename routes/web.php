<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use App\Filament\Resources\MasterPartResource\Pages\ViewSubParts;

use Illuminate\Support\Facades\Mail;
use App\Mail\CriticalStockNotification;
use App\Models\Inventory;

Route::get('/test-email', function () {
    // Ambil data produk yang stoknya di bawah atau sama dengan batas minimum
    $lowStockItems = Inventory::query()
        ->join('sub_parts', 'inventory.product_id', '=', 'sub_parts.sub_part_number')
        ->whereRaw('inventory.quantity_available <= inventory.minimum_stock')
        ->where('inventory.quantity_available', '>', 0) // Pastikan masih ada stok
        ->select('inventory.product_id', 'sub_parts.sub_part_name', 'inventory.quantity_available')
        ->get();

    if ($lowStockItems->isNotEmpty()) {
        // Kirim email ke alamat tujuan
        Mail::to('admin@perusahaan.com')->send(new CriticalStockNotification($lowStockItems));
        return "Email notifikasi stok kritis telah dikirim!";
    }

    return "Tidak ada stok kritis saat ini.";
});

// Auth::routes(); // Ini akan auto-include semua route untuk login, register, reset password, dll

// Route::get('/admin/master-parts/{masterPart}/sub-parts', ViewSubParts::class)
//     ->name('filament.admin.resources.master-parts.sub-parts');
