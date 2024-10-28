<?php

namespace App\Filament\Resources\ExitInvoiceResource\Pages;

use App\Filament\Resources\ExitInvoiceResource;
use App\Models\ExitInvoice;
use App\Models\ExitItem;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use Filament\Notifications\Notification;

class ListExitInvoices extends ListRecords
{
    protected static string $resource = ExitInvoiceResource::class;

    protected ?Item $item_in_stock = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang Keluar')
                ->icon('heroicon-o-plus')
                ->label('Tambah')
                ->modalWidth('5xl')
                ->createAnother(false)

                ->before(function (array $data) {
                    foreach ($data['exitItems'] as $exitItem) {
                        $exitItem = Item::where('code', $exitItem['code'])->first();

                        if (!$exitItem) {
                            Notification::make()
                                ->title('Barang tidak ditemukan')
                                ->danger()
                                ->send();

                            $this->halt();
                        }

                        if ($exitItem->unit_quantity < $exitItem['unit_quantity']) {
                            Notification::make()
                                ->title('Stok tidak mencukupi')
                                ->danger()
                                ->send();

                            $this->halt();
                        }
                    }
                })

                ->mutateFormDataUsing(function (array $data): array {
                    $data['date'] = now();
                    $data['total'] = 0;
                    foreach ($data['exitItems'] as $exitItem) {
                        $data['total'] += $exitItem['total_price'];
                    }
                    return $data;
                })

                ->using(function (array $data): Model {
                    // Create the invoice
                    $invoice = ExitInvoice::create($data);

                    // Process each exit item
                    foreach ($data['exitItems'] as $exitItem) {
                        $item = Item::where('code', $exitItem['code'])->first();

                        // Deduct the quantity from stock
                        $item->unit_quantity -= $exitItem['unit_quantity'];
                        $item->total_price -= $exitItem['total_price'];
                        $item->save();

                        // Create an exit item record
                        ExitItem::create([
                            'invoice_id' => $invoice->id,
                            'item_id' => $item->id,
                            'unit' => $exitItem['unit'],
                            'unit_price' => $exitItem['unit_price'],
                            'unit_quantity' => $exitItem['unit_quantity'],
                            'total_price' => $exitItem['total_price'],
                        ]);
                    }

                    return $invoice;
                }),
        ];
    }
}
