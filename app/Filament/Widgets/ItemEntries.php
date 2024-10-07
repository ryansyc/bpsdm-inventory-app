<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ItemEntryResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ItemEntries extends BaseWidget
{

    protected static ?int $sort = 1;

    protected static ?string $heading = 'Barang Masuk';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ItemEntryResource::getEloquentQuery()
            )
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', Auth::id());
            })
            ->defaultPaginationPageOption(5)
            ->defaultSort('entry_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->dateTime('Y-m-d | H:i:s')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable(),
                    // ables\Columns\TextColumn::make('description')
                    // ->label('Keterangan')
                    // ->searchable()
                    // ->sortable(),
            ]);
    }
}
