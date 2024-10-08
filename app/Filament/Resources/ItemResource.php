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

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-s-cube';

    protected static ?string $slug = 'stok-barang';

    protected static ?string $pluralModelLabel = 'stok barang';

    protected static ?int $navigationSort = 1;

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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Gudang')
                    ->searchable()
                    ->sortable()
                    ->visible(Auth::user()->role === 'super-admin'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()->role === 'admin') {
                    return $query->where('user_id', Auth::id());
                }
            })
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Gudang')
                    ->default(Auth::id())
                    ->options(User::pluck('name', 'id'))
                    ->visible(Auth::user()->role === 'super-admin'),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Gudang'),
            )
            ->persistFiltersInSession()
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
