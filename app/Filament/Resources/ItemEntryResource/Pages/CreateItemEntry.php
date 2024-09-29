<?php

namespace App\Filament\Resources\ItemEntryResource\Pages;

use App\Filament\Resources\ItemEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemEntry;
use Filament\Notifications\Notification;

class CreateItemEntry extends CreateRecord
{
    protected static string $resource = ItemEntryResource::class;

    protected ?string $heading = 'Tambah Barang Masuk';
}
