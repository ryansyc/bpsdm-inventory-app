<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ItemExitResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ItemExits extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Barang Keluar';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ItemExitResource::getEloquentQuery()
            )
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', Auth::id());
            })
            ->defaultPaginationPageOption(5)
            ->defaultSort('exit_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('exit_date')
                    ->label('Tanggal Keluar')
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
            ]);
    }
}
