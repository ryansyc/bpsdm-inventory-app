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

    protected ?Item $item_in_stock = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang Keluar')
                ->modalWidth('md')

                ->before(function (array $data) {
                    $this->item_in_stock = Item::where('user_id', Auth::id())
                        ->where('name', $data['name'])
                        ->first();

                    if (!$this->item_in_stock) {
                        Notification::make()
                            ->title('Barang tidak ditemukan')
                            ->danger()
                            ->send();

                        $this->halt();
                    }

                    if ($this->item_in_stock->quantity < $data['quantity']) {
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
                    $total_price = $data['quantity'] * $this->item_in_stock->price;
                    $this->item_in_stock->decrement('quantity', $data['quantity']);
                    $this->item_in_stock->decrement('total_price', $total_price);

                    if (Auth::user()->role === 'admin') {
                        return ItemExit::create([
                            'item_id' => $this->item_in_stock->id,
                            'exit_date' => $data['exit_date'],
                            'quantity' => $data['quantity'],
                            'total_price' => $total_price,
                            'department' => '',
                            'receiver' => $data['receiver'],
                            'user_id' => Auth::id(),
                        ]);
                    }

                    $item_in_department = Item::where('code', $data['code'])
                        ->where('user_id', $data['department'])
                        ->first();

                    if ($item_in_department) {
                        $item_in_department->update([
                            'quantity' => $item_in_department->quantity + $data['quantity'],
                            'total_price' => $item_in_department->total_price + $total_price,
                        ]);
                    } else {
                        Item::create([
                            'code' => $data['code'],
                            'name' => $data['name'],
                            'quantity' => $data['quantity'],
                            'price' => $this->item_in_stock->price,
                            'total_price' => $total_price,
                            'category_id' => $this->item_in_stock->category_id,
                            'user_id' => $data['department'],
                        ]);
                    }

                    return ItemExit::create([
                        'item_id' => $this->item_in_stock->id,
                        'exit_date' => $data['exit_date'],
                        'quantity' => $data['quantity'],
                        'total_price' => $total_price,
                        'department' => User::where('id', $data['department'])->value('name'),
                        'receiver' => $data['receiver'],
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
