<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemEntryResource\Pages;
use App\Filament\Resources\ItemEntryResource\RelationManagers;
use App\Models\Item;
use App\Models\ItemEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemEntryResource extends Resource
{
    protected static ?string $model = ItemEntry::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-down-tray';

    protected static ?string $slug = 'barang-masuk';

    protected static ?string $pluralModelLabel = 'barang masuk';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Gudang Utama';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode Barang')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('name', Item::where('code', $state)->value('name'));
                    }),
                Forms\Components\Select::make('name')
                    ->label('Nama Barang')
                    ->options(Item::where('user_id', Auth::id())->pluck('name', 'name'))
                    ->placeholder('-')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('code', Item::where('name', $state)->value('code'));
                    }),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->minValue(0)
                    ->numeric(),
                Forms\Components\TextInput::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('entry_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal')
                    ->dateTime('Y-m-d | H:i:s')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.price')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->sortable(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', Auth::id());
            })
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('dari'),
                        DatePicker::make('sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn(Builder $query, $date): Builder => $query->whereDate('entry_date', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('entry_date', '<=', $date),
                            );
                    })
            ])
            ->actions([])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListItemEntries::route('/'),
            // 'create' => Pages\CreateItemEntry::route('/create'),
            // 'edit' => Pages\EditItemEntry::route('/{record}/edit'),
        ];
    }
}
