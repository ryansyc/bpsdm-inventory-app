<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubmissions extends ListRecords
{
    protected static string $resource = SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Pengajuan Barang')
                ->icon('heroicon-o-plus')
                ->label('Tambah')
                ->modalWidth('md')
                ->createAnother(false)

                ->mutateFormDataUsing(function (array $data): array {
                    $data['date'] = now();
                    return $data;
                })
        ];
    }
}
