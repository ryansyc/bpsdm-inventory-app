<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ItemResource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Item;

class EmptyItemsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Barang Habis';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn() => ItemResource::getEloquentQuery()
                    ->where('unit_quantity', 0)
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('unit_quantity', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->unit_quantity)
                    ->formatStateUsing(
                        fn(int $state): string => match (true) {
                            $state === 0 => 'Habis',
                            $state > 0 && $state <= 5 => 'Sedikit',
                            $state > 5 => 'Tersedia',
                        }
                    )
                    ->color(
                        fn(int $state): string => match (true) {
                            $state === 0 => 'danger',
                            $state > 0 && $state <= 5 => 'warning',
                            $state > 5 => 'success',
                        }
                    )
            ]);
    }
}
