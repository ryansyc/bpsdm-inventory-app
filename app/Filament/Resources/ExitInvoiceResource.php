<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExitInvoiceResource\Pages;
use App\Filament\Resources\ExitInvoiceResource\RelationManagers;
use App\Models\Category;
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
use App\Models\ExitInvoice;
use App\Models\ExitItem;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms\Get;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Log;

class ExitInvoiceResource extends Resource
{
    protected static ?string $model = ExitInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-up-tray';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $pluralModelLabel = 'Barang Keluar';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('department_id')
                    ->label('Bidang')
                    ->options(Department::pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn(callable $set) => $set('receiver', null)),

                Forms\Components\TextInput::make('provider')
                    ->label('Nama Penyedia')
                    ->required(),

                Forms\Components\Select::make('receiver')
                    ->label('Nama Penerima')
                    ->required()
                    ->options(function (callable $get) {
                        $departmentId = $get('department_id');
                        if ($departmentId) {
                            return Submission::where('department_id', $departmentId)->pluck('name', 'name');
                        }
                        return [];
                    })
                    ->searchable(),

                Forms\Components\TextInput::make('total')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->hiddenOn('create'),

                TableRepeater::make('exitItems')
                    ->label('Barang')
                    ->columnSpanFull()
                    ->required()
                    ->live()
                    // ->afterStateUpdated(function (Get $get, Set $set) {
                    //     self::updateTotals($get, $set);
                    // })
                    // ->deleteAction(
                    //     fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)),
                    // )
                    ->headers([
                        Header::make('Kode')->width('20%'),
                        Header::make('Nama')->width('20%'),
                        Header::make('Satuan')->width('10%'),
                        Header::make('Harga Satuan')->width('20%'),
                        Header::make('Jumlah')->width('10%'),
                        Header::make('Harga')->width('20%'),
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $item = Item::where('code', $state)->first(['name', 'unit', 'unit_price']);
                                if ($item) {
                                    $set('name', $item->name);
                                    $set('unit', $item->unit);
                                    $set('unit_price', $item->unit_price);
                                    self::calculateTotal($get, $set);
                                }
                            }),
                        Forms\Components\Select::make('name')
                            ->label('Nama')
                            ->required()
                            ->options(Item::pluck('name', 'name'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $item = Item::where('name', $state)->first(['code', 'unit', 'unit_price']);
                                if ($item) {
                                    $set('code', $item->code);
                                    $set('unit', $item->unit);
                                    $set('unit_price', $item->unit_price);
                                    self::calculateTotal($get, $set);
                                }
                            }),
                        Forms\Components\TextInput::make('unit')
                            ->label('Satuan')
                            ->required(),
                        Forms\Components\TextInput::make('unit_price')
                            ->label('Harga Satuan')
                            ->required()
                            ->minValue(0)
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateTotal($get, $set);
                            }),
                        Forms\Components\TextInput::make('unit_quantity')
                            ->label('Jumlah')
                            ->required()
                            ->minValue(0)
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateTotal($get, $set);
                            }),
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total')
                            ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                            ->readOnly()
                    ]),


            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->recordUrl(null)
            ->recordAction(null)
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
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('receiver')
                    ->label('Penerima')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('provider')
                    ->label('Penyedia')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->wrap()
            ])

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
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),


                Filter::make('department')
                    ->form([
                        Forms\Components\Select::make('department_id')
                            ->label('Bidang')
                            ->options(Department::pluck('name', 'id'))
                            ->default(request()->query('id'))
                            ->disabled()
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (Auth::user()->department_id != $data['department_id']) {
                            return $query->where('department_id', $data['department_id']);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->button()
                    ->color('primary')
                    ->modalHeading('Detail Barang Keluar')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $exitItems = ExitItem::where('invoice_id', $data['id'])
                            ->select('item_id', 'unit', 'unit_price', 'unit_quantity', 'total_price')
                            ->with('item:id,code,name,unit_price')
                            ->get()
                            ->map(fn($exitItem) => [
                                'name' => $exitItem->item->name,
                                'code' => $exitItem->item->code,
                                ...$exitItem->toArray(),
                            ]);

                        $data['exitItems'] = $exitItems->toArray();

                        return $data;
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    // public static function getPluralModelLabel(): string
    // {
    //     Log::info('Get plural model label');
    //     $department_id = request()->query('id');
    //     if ($department_id) {
    //         Log::info('department_id', [$department_id]);
    //         $department = $department_id ? Department::find($department_id) : null;
    //         Log::info('department', [$department]);
    //         if ($department_id != Auth::user()->department_id) {
    //             Log::info('Showing for department', [$department_id]);
    //             return $department ? "Barang Keluar {$department->name}" : "Barang Keluar";
    //         }
    //         Log::info('Showing for current department', [Auth::user()->department_id]);
    //     }
    // }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $total = $get('unit_price') * $get('unit_quantity');
        $set('total_price',  $total);
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
            'index' => Pages\ListExitInvoices::route('/'),
            // 'create' => Pages\CreateItemExit::route('/create'),
            // 'edit' => Pages\EditItemExit::route('/{record}/edit'),
        ];
    }
}
