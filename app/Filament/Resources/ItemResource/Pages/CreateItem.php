<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use App\Models\Item;
use App\Models\ItemEntry;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItem extends CreateRecord
{
    protected static string $resource = ItemResource::class;

    protected ?string $heading = 'Tambah Stok Barang';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entry_date'] = now();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
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
    }
}
