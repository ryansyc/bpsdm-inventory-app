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
use Filament\Forms\Components\Select;
use Filament\Forms\Get;

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
                    ->options(Department::where('id', '!=', Auth::user()->department_id)->pluck('name', 'id'))
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
                    ->headers([
                        Header::make('Kode')->width('20%'),
                        Header::make('Nama')->width('30%'),
                        Header::make('Satuan')->width('10%'),
                        Header::make('Harga Satuan')->width('15%'),
                        Header::make('Jumlah')->width('10%'),
                        Header::make('Harga')->width('15%'),
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->live(debounce: 500)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $item = Item::where('code', $state)->first(['name', 'unit', 'unit_price']);
                                if ($item) {
                                    $set('name', $item->name);
                                    $set('unit', $item->unit);
                                    $set('unit_price', $item->unit_price);
                                    $set('is_disabled', true);
                                    self::calculateTotal($get, $set);
                                } else {
                                    $set('name', null);
                                    $set('unit', null);
                                    $set('unit_price', null);
                                    $set('is_disabled', false);
                                }
                            }),
                        Forms\Components\Select::make('name')
                            ->label('Nama')
                            ->required()
                            ->placeholder("")
                            ->searchable()
                            ->live(onBlur: true)
                            ->options(Item::pluck('name', 'name'))
                            ->disabled(fn(Get $get) => $get('is_disabled'))
                            ->dehydrated()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $item = Item::where('name', $state)->first(['code', 'unit', 'unit_price']);
                                if ($item) {
                                    $set('code', $item->code);
                                    $set('unit', $item->unit);
                                    $set('unit_price', $item->unit_price);
                                    self::calculateTotal($get, $set);
                                } else {
                                    $set('is_disabled', false);
                                }
                            }),
                        Forms\Components\TextInput::make('unit')
                            ->label('Satuan')
                            ->required()
                            ->dehydrated()
                            ->disabled(fn(Get $get) => $get('is_disabled')),
                        Forms\Components\TextInput::make('unit_price')
                            ->label('Harga Satuan')
                            ->required()
                            ->minValue(1)
                            ->numeric()
                            ->live(onBlur: true)
                            ->dehydrated()
                            ->disabled(fn(Get $get) => $get('is_disabled'))
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateTotal($get, $set);
                            }),
                        Forms\Components\TextInput::make('unit_quantity')
                            ->label('Jumlah')
                            ->required()
                            ->minValue(1)
                            ->numeric()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateTotal($get, $set);
                            }),
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total')
                            ->disabled()
                            ->dehydrated()
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

                Filter::make('category')
                    ->form([
                        Select::make('category')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->preload(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['category'],
                                fn(Builder $query, $category): Builder => $query->where('category_id', $category),
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
                    ->color('secondary')
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

                Tables\Actions\Action::make('nota')
                    ->button()
                    ->color('primary')
                    ->label('Nota')
                    ->icon('heroicon-s-document')
                    ->url(fn($record) => route('invoice', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('tertiary')
                    ->modalHeading('Ubah Barang Keluar')
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

                        foreach ($data['exitItems'] as $exitItem) {
                            $item = Item::where('name', $exitItem['name'])->first();
                            $item->unit_quantity += $exitItem['unit_quantity'];
                            $item->total_price += $exitItem['total_price'];
                            $item->save();
                            if ($item->unit_quantity <= 0) {
                                $item->delete();
                            }
                        }

                        return $data;
                    })
                    ->action(function ($record, array $data) {
                        ExitItem::where('invoice_id', $record->id)->delete();
                        $invoice = ExitInvoice::find($record->id);
                        $data['total'] = 0;

                        foreach ($data['exitItems'] as $exitItem) {
                            $item = Item::where('name', $exitItem['name'])->first();
                            $data['total'] += $exitItem['total_price'];

                            if ($item) {
                                $item->unit_quantity -= $exitItem['unit_quantity'];
                                $item->total_price -= $exitItem['total_price'];
                                $item->save();
                            } else {
                                $item = Item::create($exitItem);
                            }

                            ExitItem::create([
                                'invoice_id' => $invoice->id,
                                'item_id' => $item->id,
                                'unit' => $exitItem['unit'],
                                'unit_price' => $exitItem['unit_price'],
                                'unit_quantity' => $exitItem['unit_quantity'],
                                'total_price' => $exitItem['total_price'],
                            ]);
                        };

                        return $invoice->update($data);
                    }),
            ])
            ->bulkActions([]);
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $total = $get('unit_price') * $get('unit_quantity');
        $set('total_price',  $total);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExitInvoices::route('/'),
        ];
    }
}
