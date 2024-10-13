<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;

class ListSubmissions extends ListRecords
{
    protected static string $resource = SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Tambah Pengajuan Barang')
                ->modalWidth('md')

                ->mutateFormDataUsing(function (array $data): array {
                    $data['status'] = 'pending';
                    $data['description'] = '';
                    return $data;
                })

                ->using(function (array $data): Model {
                    $submission = Submission::create([
                        'name' => $data['name'],
                        'position' => $data['position'],
                        'file' => $data['file'],
                        'status' => $data['status'],
                        'description' => $data['description'],
                        'user_id' => Auth::id(),
                    ]);

                    return $submission;
                })
        ];
    }
}
