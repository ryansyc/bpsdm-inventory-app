<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ItemResource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class Items extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Stok Barang';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ItemResource::getEloquentQuery()
            )
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', Auth::id());
            })
            ->defaultPaginationPageOption(5)
            ->defaultSort('quantity', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Gudang')
                    ->searchable()
                    ->sortable()
                    ->visible(Auth::user()->role === 'super-admin'),
            ]);
    }
}
