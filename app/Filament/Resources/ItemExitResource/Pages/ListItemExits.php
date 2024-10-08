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
use Illuminate\Support\Facades\Auth;
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
                    $this->item = Item::where('user_id', Auth::id())
                        ->where('name', $data['name'])
                        ->first();

                    if (!$this->item) {
                        Notification::make()
                            ->title('Barang tidak ditemukan')
                            ->danger()
                            ->send();

                        $this->halt();
                    }

                    if ($this->item->quantity < $data['quantity']) {
                        Notification::make()
                            ->title('Stok tidak mencukupi')
                            ->danger()
                            ->send();

                        $this->halt();
                    }
                })

                ->mutateFormDataUsing(function (array $data): array {
                    $data['exit_date'] = now();
                    return $data;
                })

                ->using(function (array $data): Model {
                    $total_price = $data['quantity'] * $this->item->price;
                    $this->item->decrement('quantity', $data['quantity']);
                    $this->item->decrement('total_price', $total_price);

                    if ($data['selection'] == 0) {
                        Item::create([
                            'code' => $this->item->code,
                            'name' => $this->item->name,
                            'quantity' => $data['quantity'],
                            'price' => $this->item->price,
                            'total_price' => $total_price,
                            'category_id' => $this->item->category_id,
                            'user_id' => User::where('name', $data['description'])->value('id'),
                        ]);
                    }

                    // Create the item exit entry
                    return ItemExit::create([
                        'item_id' => $this->item->id,
                        'exit_date' => $data['exit_date'],
                        'quantity' => $data['quantity'],
                        'total_price' => $total_price,
                        'description' => $data['description'],
                        'user_id' => Auth::id(),
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
