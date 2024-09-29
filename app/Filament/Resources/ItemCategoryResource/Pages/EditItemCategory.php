<?php

namespace App\Filament\Resources\ItemCategoryResource\Pages;

use App\Filament\Resources\ItemCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemCategory extends EditRecord
{
    protected static string $resource = ItemCategoryResource::class;

    protected ?string $heading = 'Ubah Kategori Barang';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
