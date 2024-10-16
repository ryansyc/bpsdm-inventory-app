<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Filament\Resources\SubmissionResource\RelationManagers;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'ajuan-barang';

    protected static ?string $pluralModelLabel = 'ajuan barang';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file')
                    ->directory('uploads/pdf_dokumen')
                    ->required()
                    ->getUploadedFileNameForStorageUsing(
                        fn($file): string => now()->format('ymdHis') . '-' . $file->getClientOriginalName()
                    ),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('file')
                    ->color('info')
                    ->formatStateUsing(fn($state) => Str::limit(basename($state), 30))
                    ->url(fn($record) => config('app.url') . "/storage/{$record->file}")
                    ->openUrlInNewTab()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function (?string $state): string {
                        return match ($state) {
                            'pending' => 'Pending',
                            'rejected' => 'Rejected',
                            'approved' => 'Approved',
                            default => 'Unknown',
                        };
                    })
                    ->color(function (?string $state): string {
                        return match ($state) {
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            'approved' => 'success',
                            default => 'gray',
                        };
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Ubah Pengajuan')
                    ->modalWidth('sm')
                    ->color('warning')
                    ->authorize('edit', Submission::class)
                    ->action(function (Submission $record, array $data) {
                        $record->update(array_merge($data, ['status' => 'pending']));
                    }),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->authorize('approve', Submission::class)
                    ->action(function (Submission $record) {
                        $record->update(['status' => 'approved']);
                    }),
                Tables\Actions\Action::make('reject')
                    ->modalHeading('Alasan Penolakan')
                    ->modalWidth('sm')
                    ->label('Reject')
                    ->icon('heroicon-s-x-circle')
                    ->color('danger')
                    ->authorize('reject', Submission::class)
                    ->form([ // Use `form` to define form components for the action
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->required()
                            ->default(fn($record) => $record->description ?? ''), // Provide a default or empty string
                    ])
                    ->action(function (Submission $record, array $data) {
                        $record->update([
                            'description' => $data['description'],
                            'status' => 'rejected',
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubmissions::route('/'),
        ];
    }
}
