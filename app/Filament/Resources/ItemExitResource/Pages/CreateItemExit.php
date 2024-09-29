<?php

namespace App\Filament\Resources\ItemExitResource\Pages;

use App\Filament\Resources\ItemExitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\ItemExit;
use Filament\Notifications\Notification;

class CreateItemExit extends CreateRecord
{
    protected static string $resource = ItemExitResource::class;

    protected ?string $heading = 'Tambah Barang Keluar';
}
