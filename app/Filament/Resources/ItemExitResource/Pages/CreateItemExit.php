<?php

namespace App\Filament\Resources\ItemExitResource\Pages;

use App\Filament\Resources\ItemExitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemExit;
use Filament\Notifications\Notification;

class CreateItemExit extends CreateRecord
{
    protected static string $resource = ItemExitResource::class;

    protected ?string $heading = 'Tambah Barang Keluar';

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

        if ($item->quantity < $data['quantity']) {
            // Item quantity is not enough, show a notification
            Notification::make()
                ->title('Stok tidak mencukupi')
                ->body('Stok yang tersedia tidak mencukupi')
                ->danger()
                ->send();

            $this->halt();
        }

        $this->item = $item;
    }

    // Insert date to data
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['exit_date'] = now();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $item = $this->item;

        // Update item quantity
        $item->quantity -= $data['quantity'];
        $item->save();

        // Create item entry
        ItemExit::create([
            'item_id' => $item->id,
            'quantity' => $data['quantity'],
            'exit_date' => $data['exit_date'],
            'description' => $data['description'],
        ]);

        return $item;
    }
}
