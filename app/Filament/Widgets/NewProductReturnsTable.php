<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SalesOrderResource; // Asumsi Anda punya resource ini
use App\Models\ProductReturn;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class NewProductReturnsTable extends BaseWidget
{
    protected static ?int $sort = 4; // Tampil setelah KPI dan Notifikasi Penting
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Menampilkan retur 60 hari terakhir, diurutkan dari yang terbaru
                ProductReturn::query()
                    ->where('return_date', '>=', Carbon::now()->subDays(60))
                    ->orderBy('return_date', 'desc')
            )
            ->heading('Retur Produk Terbaru (Membutuhkan Proses)')
            ->columns([
                Tables\Columns\TextColumn::make('return_date')
                    ->date()
                    ->sortable()
                    ->label('Tgl Retur'),

                Tables\Columns\TextColumn::make('subPart.sub_part_name') // Asumsi relasi 'subPart' ada di model ProductReturn
                    ->label('Nama Produk')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('quantity')
                    ->numeric(),

                Tables\Columns\BadgeColumn::make('reason')
                    ->label('Alasan Retur')
                    ->colors([
                        'danger' => 'Item defective on arrival',
                        'warning' => 'Wrong item delivered',
                        'gray' => 'Customer changed mind',
                        'info' => 'Packaging damaged',
                    ]),

                Tables\Columns\IconColumn::make('condition')
                    ->label('Kondisi')
                    ->icon(fn (string $state): string => match ($state) {
                        'GOOD' => 'heroicon-o-check-circle',
                        'DAMAGED' => 'heroicon-o-x-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'GOOD' => 'success',
                        'DAMAGED' => 'danger',
                    }),
            ])
            ->actions([
                // Action ini bisa diarahkan ke halaman detail sales order terkait
                Tables\Actions\Action::make('viewOrder')
                    ->label('Lihat Order')
                    ->icon('heroicon-o-document-text')
                    ->url(fn (ProductReturn $record): string => url("/admin/sales-orders/{$record->sales_order_id}")) // Sesuaikan URL
                    ->openUrlInNewTab(),
            ]);
    }
}

// Tambahan: Pastikan di model App\Models\ProductReturn.php ada relasi ini
// public function subPart()
// {
//     return $this->belongsTo(\App\Models\SubPart::class, 'part_number', 'sub_part_number');
// }