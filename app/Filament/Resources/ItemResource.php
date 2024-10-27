<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Request;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-s-tag';

    protected static ?string $slug = 'stok-barang';

    protected static ?string $pluralModelLabel = 'stok barang';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Barang')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('unit_quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::calculateTotal($get, $set);
                    }),
                Forms\Components\TextInput::make('unit_price')
                    ->label('Harga Satuan')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        self::calculateTotal($get, $set);
                    }),
                Forms\Components\TextInput::make('total_price')
                    ->label('Total Harga')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->dehydrated()
                    ->disabled(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->numeric()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
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
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('tertiary')
                    ->button()
                    ->modalHeading('Ubah Barang')
                    ->modalWidth('md'),

                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->action(function ($record) {
                        if ($record->quantity > 0) {
                            Notification::make()
                                ->title("Stok {$record->name} masih ada")
                                ->body("Hanya bisa menghapus saat stok kosong")
                                ->danger()
                                ->send();
                            return;
                        }
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->quantity > 0) {
                                    Notification::make()
                                        ->title("Stok {$record->name} masih ada")
                                        ->body("Hanya bisa menghapus saat stok kosong")
                                        ->danger()
                                        ->send();

                                    return;
                                }
                            }
                            $records->delete();
                        }),
                ]),
            ]);
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $total = $get('unit_price') * $get('unit_quantity');
        $set('total_price',  $total);
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
            'index' => Pages\ListItems::route('/'),
            // 'create' => Pages\CreateItem::route('/create'),
            // 'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
