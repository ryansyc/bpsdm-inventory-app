<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mutation')
                ->button()
                ->color('primary')
                ->label('Mutasi')
                ->icon('heroicon-s-document')
                ->url(fn() => route('mutation'))
                ->openUrlInNewTab(),

        ];
    }
}
