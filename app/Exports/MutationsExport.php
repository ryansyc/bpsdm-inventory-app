<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\EntryInvoice;
use App\Models\ExitInvoice;
use App\Models\Item;
use Dotenv\Parser\Entry;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MutationsExport implements FromView
{
    public function view(): View
    {
        $entries = $this->getEntries();
        $exits = $this->getExits();
        $item_id = $this->getItemId($entries, $exits);
        $finalStocks = $this->getFinalStock($item_id);
        $startStocks = $this->getStartStock($item_id, $entries, $exits);
        $rowCategory = $this->getRowCategory($entries, $exits, $startStocks, $finalStocks);
        $categoryTotal = $this->getCategoryTotal($rowCategory);

        return view('exports.mutation', [
            'rowCategories' => $rowCategory,
            'categoryTotal' => $categoryTotal
        ]);
    }

    public function getCategoryTotal($rowCategory)
    {
        $collection = collect([
            'start_total' => 0,
            'entry_total' => 0,
            'exit_total' => 0,
            'final_total' => 0
        ]);

        $collection['start_total'] = $rowCategory->sum('start_total');
        $collection['entry_total'] = $rowCategory->sum('entry_total');
        $collection['exit_total'] = $rowCategory->sum('exit_total');
        $collection['final_total'] = $rowCategory->sum('final_total');

        return $collection;
    }


    public function getRowCategory($entries, $exits, $startStocks, $finalStocks)
    {
        $collection = Category::get();

        $collection->each(function ($category) use ($entries, $exits, $startStocks, $finalStocks) {
            $category->entry_items = collect();
            $category->exit_items = collect();
            $category->start_stocks = collect();
            $category->final_stocks = collect();

            foreach ($entries as $entry) {
                if ($entry->category_id == $category->id) {
                    $category->entry_items = $entry->items;

                    $stock = $startStocks->filter(function ($item) use ($category) {
                        return $category->entry_items->pluck('item_id')->contains($item->id);
                    });
                    $category->start_stocks = $category->start_stocks->merge($stock);

                    $stock = $finalStocks->filter(function ($item) use ($category) {
                        return $category->entry_items->pluck('item_id')->contains($item->id);
                    });
                    $category->final_stocks = $category->final_stocks->merge($stock);
                }
            }
            foreach ($exits as $exit) {
                if ($exit->category_id == $category->id) {
                    $category->exit_items = $exit->items;

                    $stock = $startStocks->filter(function ($item) use ($category) {
                        return $category->exit_items->pluck('item_id')->contains($item->id);
                    });
                    $category->start_stocks = $category->start_stocks->merge($stock);

                    $stock = $finalStocks->filter(function ($item) use ($category) {
                        return $category->exit_items->pluck('item_id')->contains($item->id);
                    });
                    $category->final_stocks = $category->final_stocks->merge($stock);
                }
            }

            $category->start_stocks = $category->start_stocks->unique('id');
            $category->final_stocks = $category->final_stocks->unique('id');

            $category->entry_total = $category->entry_items->isNotEmpty() ? $category->entry_items->sum('total_price') : 0;
            $category->exit_total = $category->exit_items->isNotEmpty() ? $category->exit_items->sum('total_price') : 0;
            $category->start_total = $category->start_stocks->sum('total_price');
            $category->final_total = $category->final_stocks->sum('total_price');
        });

        return $collection;
    }

    public function getEntries()
    {
        return EntryInvoice::with('items')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();
    }

    public function getExits()
    {
        return ExitInvoice::with('items')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();
    }

    public function getFinalStock($item_id)
    {
        return Item::whereIn('id', $item_id)->get()->keyBy('id');
    }

    public function getStartStock($item_id, $entries, $exits)
    {
        $collection = Item::whereIn('id', $item_id)->get()->keyBy('id');

        foreach ($entries as $invoice) {
            foreach ($invoice->items as $item) {
                if (isset($collection[$item->item_id])) {
                    $collection[$item->item_id]['unit_quantity'] -= $item->unit_quantity;
                    $collection[$item->item_id]['total_price'] -= $item->total_price;
                }
            }
        }

        foreach ($exits as $invoice) {
            foreach ($invoice->items as $item) {
                if (isset($collection[$item->item_id])) {
                    $collection[$item->item_id]['unit_quantity'] += $item->unit_quantity;
                    $collection[$item->item_id]['total_price'] += $item->total_price;
                }
            }
        }

        return $collection;
    }

    public function getItemId($entries, $exits)
    {
        $item_id = [];
        foreach ($entries as $invoice) {
            foreach ($invoice->items as $item) {
                $item_id[] = $item->item_id;
            }
        }

        foreach ($exits as $invoice) {
            foreach ($invoice->items as $item) {
                $item_id[] = $item->item_id;
            }
        }
        return array_unique($item_id);
    }
}
