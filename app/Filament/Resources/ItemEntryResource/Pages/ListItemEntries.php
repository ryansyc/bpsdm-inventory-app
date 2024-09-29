<?php

namespace App\Filament\Resources\ItemEntryResource\Pages;

use App\Filament\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemEntry;
use Filament\Notifications\Notification;

class ListItemEntries extends ListRecords
{
    protected static string $resource = ItemEntryResource::class;

    protected ?Item $item = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang Masuk')
                ->modalWidth('md')

                ->before(function (array $data) {
                    // Attempt to find the item by name
                    $item = Item::where('name', $data['name'])->first();

                    if (!$item) {
                        // Item not found, show a notification
                        Notification::make()
                            ->title('Barang tidak ditemukan')
                            ->danger()
                            ->send();

                        $this->halt(); // Stop the process
                    }

                    // Store the item for use in the `using()` method
                    $this->item = $item;
                })

                // Mutate form data before creation
                ->mutateFormDataUsing(function (array $data): array {
                    $data['entry_date'] = now(); // Set exit date
                    return $data;
                })

                // Handle the actual record creation
                ->using(function (array $data): Model {
                    $item = $this->item;

                    // Update item quantity
                    $item->quantity += $data['quantity'];
                    $item->save();

                    // Create item exit entry
                    return ItemEntry::create([
                        'item_id' => $item->id,
                        'quantity' => $data['quantity'],
                        'entry_date' => $data['entry_date'],
                        'description' => $data['description'],
                    ]);
                }),
        ];
    }
}
