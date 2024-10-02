<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemExitResource\Pages;
use App\Filament\Resources\ItemExitResource\RelationManagers;
use App\Models\ItemExit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemExitResource extends Resource
{
    protected static ?string $model = ItemExit::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-up-tray';

    protected static ?string $slug = 'barang-keluar';

    protected static ?string $pluralModelLabel = 'barang keluar';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('name')
                    ->label('Nama Barang')
                    ->relationship('item', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->minValue(0)
                    ->numeric(),
                Forms\Components\Radio::make('selection')
                    ->label('Choose an option')
                    ->options([
                        1 => 'Grup',
                        0 => 'Orang',
                    ])
                    ->required()
                    ->reactive(),

                Forms\Components\Select::make('selection_box')
                    ->label('Select an Item')
                    ->options([
                        'item1' => 'Item 1',
                        'item2' => 'Item 2',
                        'item3' => 'Item 3',
                    ])
                    ->required()
                    ->visible(fn(callable $get) => $get('selection') === 1),

                Forms\Components\TextInput::make('input_text')
                    ->label('Input Text')
                    ->required()
                    ->visible(fn(callable $get) => $get('selection') === 0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('exit_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
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
                    ->label('Deskripsi')
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListItemExits::route('/'),
            // 'create' => Pages\CreateItemExit::route('/create'),
            // 'edit' => Pages\EditItemExit::route('/{record}/edit'),
        ];
    }
}
