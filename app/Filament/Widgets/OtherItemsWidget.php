<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ItemResource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\UserResource;

class OtherItemsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Stok Barang Bidang';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn(): \Illuminate\Database\Eloquent\Builder => ItemResource::getEloquentQuery()
                    ->where('user_id', '!=', Auth::id())
                    ->where('quantity', '>', 0)
            )
            ->defaultPaginationPageOption(5)
            ->defaultSort('quantity', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(
                        fn($record): int => $record->quantity
                    )
                    ->formatStateUsing(
                        fn(int $state): string => match (true) {
                            $state === 0 => 'Habis',
                            $state > 0 && $state < 10 => 'Sedikit',
                            $state >= 10 => 'Tersedia',
                        }
                    )
                    ->color(
                        fn(int $state): string => match (true) {
                            $state === 0 => 'danger',
                            $state > 0 && $state < 10 => 'warning',
                            $state >= 10 => 'success',
                        }
                    )
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user.name')
                    ->label('Bidang')
                    ->options(
                        UserResource::getEloquentQuery()
                            ->where('id', '!=', Auth::id())
                            ->pluck('name', 'id')
                    )
            ]);
    }

    public static function canView(): bool
    {
        return in_array(Auth::user()->role, ['super-admin']);
    }
}
