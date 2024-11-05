<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Models\Department;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;

    protected static ?string $navigationIcon = 'heroicon-s-inbox-arrow-down';

    protected static ?string $slug = 'ajuan-barang';

    protected static ?string $pluralModelLabel = 'ajuan barang';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('department_id')
                    ->label('Bidang')
                    ->required()
                    ->options(fn() => Department::where('id', '!=', Auth::user()->department_id)->pluck('name', 'id')->toArray())
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('position')
                    ->label('Jabatan')
                    ->required(),
                Forms\Components\FileUpload::make('file')
                    ->directory('uploads/submissions')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->with('department');
            })
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->dateTime('Y-m-d | H:i:s')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
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
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('tertiary')
                    ->modalHeading('Ubah Ajuan'),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->modalHeading('Hapus Ajuan'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubmissions::route('/'),
        ];
    }
}
