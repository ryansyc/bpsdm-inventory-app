<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemExitResource\Pages;
use App\Filament\Resources\ItemExitResource\RelationManagers;
use App\Models\ItemExit;
use App\Models\User;
use App\Models\Item;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use App\Models\Department;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemExitResource extends Resource
{
    protected static ?string $model = ItemExit::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-up-tray';

    protected static ?string $slug = 'barang-keluar';

    protected static ?string $pluralModelLabel = 'barang keluar';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode Barang')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('name', Item::where('code', $state)->value('name'));
                    }),

                Forms\Components\Select::make('name')
                    ->label('Nama Barang')
                    ->options(Item::where('user_id', Auth::id())->pluck('name', 'name'))
                    ->placeholder('-')
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('code', Item::where('name', $state)->value('code'));
                    }),

                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->required()
                    ->minValue(1)
                    ->numeric(),

                // Forms\Components\Radio::make('selection')
                //     ->label('Pilih Penerima')
                //     ->options([
                //         0 => 'Gudang',
                //         1 => 'Orang',
                //     ])
                //     ->default(0)
                //     ->live()
                //     ->required(),

                Forms\Components\Select::make('department')
                    ->label('Bidang Penerima')
                    ->options(User::where('id', '!=', Auth::id())->pluck('name', 'id'))
                    ->placeholder('-')
                    ->required()
                    ->searchable()
                    ->live(),

                Forms\Components\Select::make('receiver')
                    ->label('Nama Penerima')
                    ->options(function (callable $get) {
                        $selectedDepartmentId = $get('department');
                        return Submission::where('user_id', $selectedDepartmentId)
                            ->pluck('name', 'name');
                    })
                    ->placeholder('-')
                    ->required()
                    ->searchable()
                    ->disabled(function (Forms\Get $get) {
                        return $get('department') === null;
                    }),

                Forms\Components\TextInput::make('receiver')
                    ->label('Nama Penerima')
                    ->required()
                    ->visible(Auth::user()->role === 'admin'),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('exit_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('60px'),
                Tables\Columns\TextColumn::make('exit_date')
                    ->label('Tanggal')
                    ->dateTime('Y-m-d | H:i:s')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item.price')
                    ->label('Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('receiver')
                    ->label('Penerima')
                    ->searchable()
                    ->sortable(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where('user_id', Auth::id());
            })
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('dari'),
                        DatePicker::make('sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn(Builder $query, $date): Builder => $query->whereDate('entry_date', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('entry_date', '<=', $date),
                            );
                    })
            ])
            ->actions([])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListItemExits::route('/'),
            // 'create' => Pages\CreateItemExit::route('/create'),
            // 'edit' => Pages\EditItemExit::route('/{record}/edit'),
        ];
    }
}
