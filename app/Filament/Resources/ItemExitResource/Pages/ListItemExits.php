<?php

namespace App\Filament\Resources\ItemExitResource\Pages;

use App\Filament\Resources\ItemExitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItemExits extends ListRecords
{
    protected static string $resource = ItemExitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
