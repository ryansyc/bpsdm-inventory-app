<?php

namespace App\Filament\Resources\ItemExitResource\Pages;

use App\Filament\Resources\ItemExitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemExit;
use Filament\Notifications\Notification;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListItemExits extends ListRecords
{
    protected static string $resource = ItemExitResource::class;

    protected ?Item $item = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang Keluar')
                ->modalWidth('md')

                ->before(function (array $data) {
                    // Attempt to find the item by name
                    $item = Item::where('id', $data['name'])->first();

                    if (!$item) {
                        // Item not found, show a notification
                        Notification::make()
                            ->title('Barang tidak ditemukan')
                            ->danger()
                            ->send();

                        $this->halt(); // Stop the process
                    }

                    if ($item->quantity < $data['quantity']) {
                        // Item quantity is not enough, show a notification
                        Notification::make()
                            ->title('Stok tidak mencukupi')
                            ->danger()
                            ->send();

                        $this->halt(); // Stop the process
                    }

                    // Store the item for use in the `using()` method
                    $this->item = $item;
                })

                // Mutate form data before creation
                ->mutateFormDataUsing(function (array $data): array {
                    $data['exit_date'] = now(); // Set exit date
                    return $data;
                })

                // Handle the actual record creation
                ->using(function (array $data): Model {
                    $item = $this->item;

                    // Reduce the item quantity and save once
                    $item->decrement('quantity', $data['quantity']);

                    // Retrieve only the user's name with a single, efficient query
                    $userName = User::where('id', $data['description'])->value('name');

                    if ($data['selection'] == 0) {
                        // Batch insert a new item with minimal overhead
                        Item::create([
                            'name' => $item->name,
                            'quantity' => $data['quantity'],
                            'category_id' => $item->category_id,
                            'user_id' => $data['description'], // User ID remains
                        ]);
                    }

                    // Create the item exit entry
                    return ItemExit::create([
                        'item_id' => $item->id,
                        'quantity' => $data['quantity'],
                        'exit_date' => $data['exit_date'],
                        'description' => $userName ?: 'Unknown User', // Store the user's name in description
                    ]);
                }),

            ExportAction::make()
                ->label('Export')
                ->exports([
                    ExcelExport::make('table')
                        ->withFilename(date('Y-m-d') . ' - Barang Keluar')
                        ->withColumns([
                            Column::make('exit_date')
                                ->heading('Tanggal Keluar'),
                            Column::make('item.name')
                                ->heading('Nama Barang'),
                            Column::make('quantity')
                                ->heading('Jumlah'),
                            Column::make('description')
                                ->heading('Deskripsi'),
                        ])
                ]),
        ];
    }
}
