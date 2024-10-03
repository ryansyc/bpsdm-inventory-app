<?php

namespace App\Filament\Resources\ItemEntryResource\Pages;

use App\Filament\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemEntry;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

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
                    // Attempt to find the item by name, only select 'id' and 'quantity' for efficiency
                    $item = Item::where('name', $data['name'])->select('id', 'quantity')->first();

                    if (!$item) {
                        // Item not found, show a notification and halt
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
                    // Set entry date in a minimal way
                    $data['entry_date'] = now();
                    return $data;
                })

                // Handle the actual record creation
                ->using(function (array $data): Model {
                    // Use efficient increment method to update quantity directly
                    $this->item->increment('quantity', $data['quantity']);

                    // Create item entry without reloading the `Item` model
                    return ItemEntry::create([
                        'item_id'    => $this->item->id,
                        'quantity'   => $data['quantity'],
                        'entry_date' => $data['entry_date'],
                        'description' => $data['description'],
                        'user_id'    => Auth::id(),
                    ]);
                }),

            ExportAction::make()
                ->label('Export')
                ->exports([
                    ExcelExport::make('table')
                        ->withFilename(date('Y-m-d') . ' - Barang Masuk')
                        ->withColumns([
                            Column::make('entry_date')
                                ->heading('Tanggal Masuk'),
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
