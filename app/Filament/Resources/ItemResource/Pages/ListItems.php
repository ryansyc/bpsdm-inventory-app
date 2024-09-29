<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemEntry;

class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang')
                ->modalWidth('md')

                ->mutateFormDataUsing(function (array $data): array {
                    $data['entry_date'] = now(); // Set exit date
                    return $data;
                })

                ->using(function (array $data): Model {
                    // Create the item record
                    $item = Item::create([
                        'name' => $data['name'],
                        'quantity' => $data['quantity'],
                        'category_id' => $data['category_id'],
                    ]);

                    // Create the item entry record
                    ItemEntry::create([
                        'entry_date' => $data['entry_date'],
                        'item_id' => $item->id,
                        'quantity' => $data['quantity'],
                        'description' => $data['description'],
                    ]);

                    return $item; // Return the created item
                })
        ];
    }
}
