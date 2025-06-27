<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Inventory; // Atau SubPart jika stok ada di sana
use App\Models\SubPart; // Untuk mendapatkan nama part
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class LowStockItemsTable extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full'; // Atau sesuaikan, misal 1 jika ingin 2 kolom

    protected function getTableHeading(): string
    {
        return 'Item dengan Stok Menipis';
    }

    public function table(Table $table): Table
    {
        $lowStockThreshold = 10; // Sesuaikan ambang batas

        return $table
            ->query(
                // Sesuaikan query ini dengan struktur database Anda
                Inventory::query()
                    ->join('sub_parts', 'inventories.sub_part_number', '=', 'sub_parts.sub_part_number')
                    ->join('master_part', 'sub_parts.part_number', '=', 'master_part.part_number') // Join ke master_part untuk nama master
                    ->where('inventories.quantity', '<', $lowStockThreshold)
                    ->where('inventories.quantity', '>', 0)
                    ->select('sub_parts.sub_part_number', 'sub_parts.sub_part_name', 'master_part.part_name as master_part_name', 'inventories.quantity')
                    ->orderBy('inventories.quantity', 'asc')
                // Alternatif jika stok ada di SubPart:
                // SubPart::query()
                //     ->where('stock_quantity', '<', $lowStockThreshold)
                //     ->where('stock_quantity', '>', 0)
                //     ->orderBy('stock_quantity', 'asc')
            )
            ->columns([
                TextColumn::make('sub_part_number')
                    ->label('Kode Sub Part')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sub_part_name')
                    ->label('Nama Sub Part')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('master_part_name') // Menampilkan nama master part
                    ->label('Nama Master Part')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity') // atau 'stock_quantity'
                    ->label('Sisa Stok')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('quantity', 'asc') // Atau 'stock_quantity'
            ->paginated(false); // Nonaktifkan paginasi jika daftarnya pendek
    }

    public static function canView(): bool
    {
        // Tambahkan logika jika widget ini hanya boleh dilihat oleh role tertentu
        return true;
    }
}