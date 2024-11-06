<?php

namespace App\Filament\Resources\EntryInvoiceResource\Pages;

use App\Filament\Resources\EntryInvoiceResource;
use App\Models\EntryInvoice;
use App\Models\EntryItem;
use App\Models\Item;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;

class ListEntryInvoices extends ListRecords
{
    protected static string $resource = EntryInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Barang Masuk')
                ->icon('heroicon-o-plus')
                ->label('Tambah')
                ->modalWidth('5xl')
                ->createAnother(false)

                ->mutateFormDataUsing(function (array $data): array {
                    $data['date'] = now();
                    $data['total'] = 0;
                    foreach ($data['entryItems'] as $entryItem) {
                        $data['total'] += $entryItem['total_price'];
                    }
                    return $data;
                })

                ->using(function ($data): Model {
                    $invoice = EntryInvoice::create($data);

                    foreach ($data['entryItems'] as $entryItem) {
                        $item = Item::where('code', $entryItem['code'])->first();

                        if ($item) {
                            $item->unit_quantity += $entryItem['unit_quantity'];
                            $item->total_price += $entryItem['total_price'];
                            $item->save();
                        } else {
                            $item = Item::create($entryItem);
                        }

                        EntryItem::create([
                            'invoice_id' => $invoice->id,
                            'item_id' => $item->id,
                            'unit' => $entryItem['unit'],
                            'unit_price' => $entryItem['unit_price'],
                            'unit_quantity' => $entryItem['unit_quantity'],
                            'total_price' => $entryItem['total_price'],
                        ]);
                    };

                    return $invoice;
                }),
        ];
    }
}
