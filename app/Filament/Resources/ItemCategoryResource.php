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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemCategoryResource extends Resource
{
    protected static ?string $model = ItemCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'kategori-barang';

    protected static ?string $pluralModelLabel = 'kategori barang';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
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
                    ->label('Nama Kategori')
                    ->alignLeft()
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->modalHeading('Ubah Kategori Barang')
                    ->modalWidth('md'),

                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Hapus Kategori Barang')
                    ->action(function (ItemCategory $record) {
                        // Check for associated items before deleting
                        if ($record->items()->count() > 0) {
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
            ])

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
            'index' => Pages\ListItemCategories::route('/'),
            // 'create' => Pages\CreateItemCategory::route('/create'),
            // 'edit' => Pages\EditItemCategory::route('/{record}/edit'),
        ];
    }
}
