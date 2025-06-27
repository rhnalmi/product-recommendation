<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\InventoryMovement;
use App\Models\SubPart; // Untuk mendapatkan nama part
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn; // Untuk tipe pergerakan
use Illuminate\Database\Eloquent\Builder;

class RecentInventoryMovementsTable extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return 'Pergerakan Inventaris Terkini';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                InventoryMovement::query()
                    ->join('sub_parts', 'inventory_movements.sub_part_number', '=', 'sub_parts.sub_part_number')
                    ->select('inventory_movements.*', 'sub_parts.sub_part_name')
                    ->latest('inventory_movements.movement_date') // Urutkan berdasarkan tanggal terbaru
                    ->limit(10) // Ambil 10 pergerakan terakhir
            )
            ->columns([
                TextColumn::make('movement_date')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('sub_part_number')
                    ->label('Kode Sub Part')
                    ->searchable(),
                TextColumn::make('sub_part_name')
                    ->label('Nama Sub Part')
                    ->searchable(),
                TextColumn::make('quantity_changed')
                    ->label('Jumlah')
                    ->numeric()
                    ->formatStateUsing(fn (InventoryMovement $record): string => ($record->movement_type === 'in' ? '+' : '-') . abs($record->quantity_changed)),
                BadgeColumn::make('movement_type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'in',  // Barang Masuk
                        'danger' => 'out', // Barang Keluar
                        'warning' => 'adjustment', // Penyesuaian
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                        'adjustment' => 'Penyesuaian',
                        default => ucfirst($state),
                    }),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->tooltip(fn (InventoryMovement $record): ?string => $record->notes),
                TextColumn::make('user.name') // Asumsi ada relasi ke user yang melakukan
                    ->label('Oleh')
                    ->default('-'),
            ])
            ->paginated(false);
    }
}