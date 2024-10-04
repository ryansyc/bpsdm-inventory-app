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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemEntryResource extends Resource
{
    protected static ?string $model = ItemEntry::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-down-tray';

    protected static ?string $slug = 'barang-masuk';

    protected static ?string $pluralModelLabel = 'barang masuk';

    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('code')
                    ->label('Kode Barang')
                    ->relationship('item', 'code')
                    ->placeholder('-')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(Set $set, $state) => $set('name', Item::find($state)->name)),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255),
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
                    ->label('Deskripsi')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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
