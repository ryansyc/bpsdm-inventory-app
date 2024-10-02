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
                    $data['entry_date'] = now();
                    $data['user_id'] = Auth::id();
                    return $data;
                })

                ->using(function (array $data): Model {
                    // Create the item record
                    $item = Item::create([
                        'name' => $data['name'],
                        'quantity' => $data['quantity'],
                        'category_id' => $data['category_id'],
                        'user_id' => $data['user_id'],
                    ]);

                    // Create the item entry record
                    ItemEntry::create([
                        'entry_date' => $data['entry_date'],
                        'item_id' => $item->id,
                        'quantity' => $data['quantity'],
                        'description' => $data['description'],
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
