<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterPartResource\Pages;
use App\Filament\Resources\MasterPartResource\RelationManagers;
use App\Models\MasterPart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;


class MasterPartResource extends Resource
{
    protected static ?string $model = MasterPart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('part_number')
                    ->required()
                    ->maxLength(255)
                    // Jika Anda ingin field ini readonly saat edit, tambahkan ->disabledOn('edit'),
                    // tapi untuk impor, ini adalah kunci utama.
                    ->label('Nomor Part'),
                Forms\Components\TextInput::make('part_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Part'),
                Forms\Components\TextInput::make('part_price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Part'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('part_number')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Part'),
                Tables\Columns\TextColumn::make('part_name')
                    ->searchable()
                    ->label('Nama Part'),
                Tables\Columns\TextColumn::make('part_price')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga Part'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('harga_diatas_100rb')
                    ->label('Harga > 100.000')
                    ->query(fn (Builder $query): Builder => $query->where('part_price', '>', 100000)),
                Filter::make('harga_dibawah_100rb')
                    ->label('Harga <= 100.000')
                    ->query(fn (Builder $query): Builder => $query->where('part_price', '<=', 100000)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Biarkan EditAction jika masih diperlukan
                \Filament\Tables\Actions\Action::make('viewDetails') // Aksi lihat detail Anda
                    ->label('View Detail')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => route('filament.admin.resources.master-parts.sub-parts', ['part_number' => $record->part_number]))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(), // Tambahkan DeleteAction jika diperlukan
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterParts::route('/'),
            //'create' => Pages\CreateMasterPart::route('/create'),
            'edit' => Pages\EditMasterPart::route('/{record}/edit'),
            'sub-parts' => Pages\ViewSubParts::route('/{part_number}/sub-parts'),
        ];
    }

}
