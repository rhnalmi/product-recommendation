<?php
// File: app/Filament/Widgets/LowStockItemsTable.php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Inventory;
use Filament\Tables\Columns\TextColumn;
use App\Mail\CriticalStockNotification;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Actions\Action;
// --- TAMBAHKAN USE STATEMENT INI ---
use Filament\Notifications\Notification;

class LowStockItemsTable extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $lowStockThreshold = 10;
        $inventoryTable = (new Inventory())->getTable();

        return $table
            ->query(
                Inventory::query()
                    ->join('sub_parts', "{$inventoryTable}.product_id", '=', 'sub_parts.sub_part_number')
                    ->join('master_part', 'sub_parts.part_number', '=', 'master_part.part_number')
                    ->where("{$inventoryTable}.quantity_available", '<', $lowStockThreshold)
                    ->where("{$inventoryTable}.quantity_available", '>', 0)
                    ->select(
                        'sub_parts.sub_part_number',
                        'sub_parts.sub_part_name',
                        'master_part.part_name as master_part_name',
                        "{$inventoryTable}.quantity_available"
                    )
            )
            ->columns([
                TextColumn::make('sub_part_number')->label('Kode Sub Part')->searchable()->sortable(),
                TextColumn::make('sub_part_name')->label('Nama Sub Part')->searchable()->sortable(),
                TextColumn::make('master_part_name')->label('Nama Master Part')->searchable()->sortable(),
                TextColumn::make('quantity_available')->label('Sisa Stok')->numeric()->sortable(),
            ])
            ->defaultSort('quantity_available', 'asc')
            ->paginated(true)
            ->headerActions([
                Action::make('send_notification')
                    ->label('Kirim Notifikasi Email')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    // --- PERBAIKI BAGIAN ACTION DI BAWAH INI ---
                    ->action(function () {
                        $inventoryTableForAction = (new Inventory())->getTable();
                        $lowStockItems = Inventory::query()
                            ->join('sub_parts', "{$inventoryTableForAction}.product_id", '=', 'sub_parts.sub_part_number')
                            ->whereRaw("{$inventoryTableForAction}.quantity_available <= {$inventoryTableForAction}.minimum_stock")
                            ->where("{$inventoryTableForAction}.quantity_available", '>', 0)
                            ->select("{$inventoryTableForAction}.product_id", 'sub_parts.sub_part_name', "{$inventoryTableForAction}.quantity_available")
                            ->get();

                        if ($lowStockItems->isNotEmpty()) {
                            Mail::to('mazhar1902@gmail.com')->send(new CriticalStockNotification($lowStockItems));
                            // Gunakan class Notification untuk mengirim notifikasi sukses
                            Notification::make()
                                ->title('Email Terkirim')
                                ->success()
                                ->send();
                        } else {
                            // Gunakan class Notification untuk mengirim notifikasi info
                            Notification::make()
                                ->title('Tidak ada stok kritis untuk dilaporkan')
                                ->info()
                                ->send();
                        }
                    }),
            ]);
    }
}