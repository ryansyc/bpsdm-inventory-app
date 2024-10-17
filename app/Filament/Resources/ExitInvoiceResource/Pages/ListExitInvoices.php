<?php

namespace App\Filament\Resources\ExitInvoiceResource\Pages;

use App\Filament\Resources\ExitInvoiceResource;
use App\Models\ExitInvoice;
use App\Models\ExitItem;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;

class ListExitInvoices extends ListRecords
{
    protected static string $resource = ExitInvoiceResource::class;

    protected ?Item $item_in_stock = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang Keluar')

                ->before(function (array $data) {
                    foreach ($data['exitItems'] as $exitItemData) {
                        // Get the item by code
                        $exitItem = Item::where('code', $exitItemData['code'])->first();

                        // If the item doesn't exist, notify the user
                        if (!$exitItem) {
                            Notification::make()
                                ->title('Barang tidak ditemukan')
                                ->danger()
                                ->send();

                            $this->halt();
                        }

                        // If the stock is not sufficient, notify the user
                        if ($exitItem->unit_quantity < $exitItemData['unit_quantity']) {
                            Notification::make()
                                ->title('Stok tidak mencukupi')
                                ->danger()
                                ->send();

                            $this->halt();
                        }
                    }
                })

                ->mutateFormDataUsing(function (array $data): array {
                    // Set current date and calculate the total
                    $data['date'] = now();
                    $data['total'] = 0;

                    foreach ($data['exitItems'] as $exitItemData) {
                        $data['total'] += $exitItemData['total_price'];
                    }

                    return $data;
                })

                ->using(function (array $data): Model {
                    // Create the invoice
                    $invoice = ExitInvoice::create($data);

                    // Process each exit item
                    foreach ($data['exitItems'] as $exitItemData) {
                        $item = Item::where('code', $exitItemData['code'])->first();

                        // Deduct the quantity from stock
                        $item->unit_quantity -= $exitItemData['unit_quantity'];
                        $item->total_price -= $exitItemData['total_price'];
                        $item->save();

                        // Create an exit item record
                        ExitItem::create([
                            'invoice_id' => $invoice->id,
                            'item_id' => $item->id,
                            'unit' => $exitItemData['unit'],
                            'unit_price' => $exitItemData['unit_price'],
                            'unit_quantity' => $exitItemData['unit_quantity'],
                            'total_price' => $exitItemData['total_price'],
                        ]);
                    }

                    return $invoice;
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
