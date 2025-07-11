<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Builder;

class DamagedStockReport extends BaseWidget
{
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Inventory::query()
                    ->join('sub_parts', 'inventory.product_id', '=', 'sub_parts.sub_part_number')
                    ->where('inventory.quantity_damaged', '>', 0)
                    ->select('inventory.*', 'sub_parts.sub_part_name', 'sub_parts.price')
            )
            ->heading('Laporan Stok Rusak')
            ->columns([
                Tables\Columns\TextColumn::make('product_id')
                    ->label('Kode Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sub_part_name')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity_damaged')
                    ->label('Jml Rusak')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Satuan')
                    ->money('IDR'),
                // Kolom kalkulasi untuk total kerugian per item
                Tables\Columns\TextColumn::make('total_loss')
                    ->label('Potensi Kerugian')
                    ->money('IDR')
                    ->getStateUsing(fn ($record) => $record->quantity_damaged * $record->price),
            ])
            ->emptyStateHeading('Tidak ada stok rusak yang tercatat.');
    }
}