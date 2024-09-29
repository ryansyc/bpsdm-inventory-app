<?php

namespace App\Filament\Resources\ItemEntryResource\Pages;

use App\Filament\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemEntry;
use Filament\Notifications\Notification;

class CreateItemEntry extends CreateRecord
{
    protected static string $resource = ItemEntryResource::class;

    protected ?string $heading = 'Tambah Barang Masuk';

    protected ?Item $item = null;

    protected function beforeCreate(): void
    {
        $data = $this->data;

        // Attempt to find the item by name
        $item = Item::where('name', $data['name'])->first();

        if (!$item) {
            // Item not found, show a notification
            Notification::make()
                ->title('Barang tidak ditemukan')
                ->body('Barang yang anda cari tidak ditemukan')
                ->danger()
                ->send();

            $this->halt();
        }

        $this->item = $item;
    }

    // Insert date to data
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entry_date'] = now();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $item = $this->item;

        // Update item quantity
        $item->quantity += $data['quantity'];
        $item->save();

        // Create item entry
        ItemEntry::create([
            'item_id' => $item->id,
            'quantity' => $data['quantity'],
            'entry_date' => $data['entry_date'],
            'description' => $data['description'],
        ]);

        return $item;
    }
}
