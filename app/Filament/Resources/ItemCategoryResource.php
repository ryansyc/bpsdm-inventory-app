<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemCategoryResource\Pages;
use App\Filament\Resources\ItemCategoryResource\RelationManagers;
use App\Models\ItemCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemCategoryResource extends Resource
{
    protected static ?string $model = ItemCategory::class;

    protected static ?string $navigationIcon = 'heroicon-s-tag';

    protected static ?string $slug = 'kategori-barang';

    protected static ?string $pluralModelLabel = 'kategori barang';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Gudang Utama';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->debounce(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori')
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->modalHeading('Ubah Kategori Barang')
                    ->modalWidth('md'),

                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Hapus Kategori Barang')
                    ->action(function (ItemCategory $record) {
                        // Check for associated items before deleting
                        if ($record->items()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak dapat menghapus kategori')
                                ->body('Masih ada barang yang menggunakannya')
                                ->send();

                            return;
                        }

                        // Proceed to delete the record
                        $record->delete();

                        // Send success notification
                        Notification::make()
                            ->success()
                            ->title('Kategori berhasil dihapus.')
                            ->send();
                    })
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
            'index' => Pages\ListItemCategories::route('/'),
            // 'create' => Pages\CreateItemCategory::route('/create'),
            // 'edit' => Pages\EditItemCategory::route('/{record}/edit'),
        ];
    }
}
