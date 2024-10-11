<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
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

    protected static ?string $slug = 'stok-barang';

    protected static bool $shouldRegisterNavigation = false;

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
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->required()
                    ->relationship('category', 'name')
                    ->placeholder('-'),
                Forms\Components\TextInput::make('price')
                    ->label('Harga Satuan')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('description')
                    ->label('Penerima')
                    ->required()
                    ->maxLength(255)
                    ->hiddenOn('edit'),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordAction(null)
            ->modifyQueryUsing(fn($query) => static::applyFilter($query))
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->quantity)
                    ->formatStateUsing(function ($state) {
                        if ($state === 0) {
                            return 'Habis';
                        } elseif ($state < 10) {
                            return 'Sedikit';
                        } else {
                            return 'Tersedia';
                        }
                    })
                    ->color(function (string $state): string {
                        return match (true) {
                            $state === 0 => 'danger',
                            $state < 10 => 'warning',
                            default => 'success',
                        };
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->modalHeading('Ubah Barang')
                    ->modalWidth('md'),

                Tables\Actions\DeleteAction::make()
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

    protected static function applyFilter($query)
    {
        $bidang = request()->query('id');
        return $query->where('user_id', $bidang);
    }

    public static function getPluralModelLabel(): string
    {
        $userId = request()->query('id');
        $user = $userId ? User::find($userId) : null;
        return $user ? "Stok Barang {$user->name}" : 'Stok Barang';
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
