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
                    $this->item = Item::where('name', $data['name'])->first(['id', 'quantity']);

                    if (!$this->item) {
                        Notification::make()
                            ->title('Barang tidak ditemukan')
                            ->danger()
                            ->send();

                        $this->halt();
                    }
                })

                ->mutateFormDataUsing(function (array $data): array {
                    $data['entry_date'] = now();
                    return $data;
                })

                ->using(function (array $data): Model {
                    $this->item->increment('quantity', $data['quantity']);

                    return ItemEntry::create([
                        'item_id'    => $this->item->id,
                        'user_id'    => Auth::id(),
                    ] + $data);
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
