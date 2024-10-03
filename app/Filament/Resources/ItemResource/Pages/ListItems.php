<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemEntry;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use Filament\Notifications\Notification;


class ListItems extends ListRecords
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang')
                ->modalWidth('md')

                ->before(function (array $data) {
                    // Check if name or code already exists
                    $exists = Item::where(function ($query) use ($data) {
                        $query->where('name', $data['name'])
                            ->orWhere('code', $data['code']);
                    })
                        ->where('user_id', Auth::id())
                        ->exists();

                    if ($exists) {
                        Notification::make()
                            ->title('Barang sudah ada')
                            ->danger()
                            ->send();

                        $this->halt(); // Stop the creation process
                    }
                })

                ->mutateFormDataUsing(function (array $data): array {
                    $data['entry_date'] = now();
                    return $data;
                })

                ->using(function (array $data): Model {
                    // Create the item record
                    $item = Item::create([
                        'code' => $data['code'],
                        'name' => $data['name'],
                        'quantity' => $data['quantity'],
                        'category_id' => $data['category_id'],
                        'user_id' => Auth::id(),
                    ]);

                    // Create the item entry record
                    ItemEntry::create([
                        'entry_date' => $data['entry_date'],
                        'item_id' => $item->id,
                        'quantity' => $data['quantity'],
                        'description' => $data['description'],
                        'user_id' => Auth::id(),
                    ]);

                    return $item; // Return the created item
                }),

            ExportAction::make()
                ->label('Export')
                ->exports([
                    ExcelExport::make('table')
                        ->withFilename(date('Y-m-d') . ' - Stok Barang')
                        ->withColumns([
                            Column::make('name')
                                ->heading('Nama Barang'),
                            Column::make('category.name')
                                ->heading('Kategori'),
                            Column::make('quantity')
                                ->heading('Jumlah'),
                        ])
                ]),
        ];
    }
}
