<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntryInvoiceResource\Pages;
use App\Models\Category;
use App\Models\EntryInvoice;
use App\Models\EntryItem;
use App\Models\Item;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;


class EntryInvoiceResource extends Resource
{
    protected static ?string $model = EntryInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-down-tray';

    protected static ?string $slug = 'barang-masuk';

    protected static ?string $pluralModelLabel = 'barang masuk';

    protected static ?int $navigationSort = 4;

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

                TableRepeater::make('entryItems')
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
                            ->live()
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
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->live(onBlur: true)
                            ->dehydrated()
                            ->disabled(fn(Get $get) => $get('is_disabled'))
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
                Forms\Components\FileUpload::make('file')
                    ->columnSpanFull()
                    ->directory('uploads/entry_invoices')
                    ->required()
                    ->getUploadedFileNameForStorageUsing(
                        fn($file): string => now()->format('ymdHis') . '-' . $file->getClientOriginalName()
                    ),
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
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('file')
                    ->color('info')
                    ->formatStateUsing(fn($state) => Str::limit(basename($state), 30))
                    ->url(fn($record) => config('app.url') . "/storage/{$record->file}")
                    ->openUrlInNewTab()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('date')
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->button()
                    ->color('secondary')
                    ->modalHeading('Detail Barang Masuk')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $entryItems = EntryItem::where('invoice_id', $data['id'])
                            ->select('item_id', 'unit', 'unit_price', 'unit_quantity', 'total_price')
                            ->with('item:id,code,name,unit_price')
                            ->get()
                            ->map(fn($entryItem) => [
                                'name' => $entryItem->item->name,
                                'code' => $entryItem->item->code,
                                ...$entryItem->toArray(),
                            ]);

                        $data['entryItems'] = $entryItems->toArray();

                        return $data;
                    }),
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('tertiary')
                    ->modalHeading('Ubah Barang Masuk')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $entryItems = EntryItem::where('invoice_id', $data['id'])
                            ->select('item_id', 'unit', 'unit_price', 'unit_quantity', 'total_price')
                            ->with('item:id,code,name,unit_price')
                            ->get()
                            ->map(fn($entryItem) => [
                                'name' => $entryItem->item->name,
                                'code' => $entryItem->item->code,
                                ...$entryItem->toArray(),
                            ]);

                        $data['entryItems'] = $entryItems->toArray();

                        foreach ($data['entryItems'] as $entryItem) {
                            $item = Item::where('name', $entryItem['name'])->first();
                            $item->unit_quantity -= $entryItem['unit_quantity'];
                            $item->total_price -= $entryItem['total_price'];
                            $item->save();
                            if ($item->unit_quantity <= 0) {
                                $item->delete();
                            }
                        }

                        return $data;
                    })
                    ->action(function ($record, array $data) {
                        EntryItem::where('invoice_id', $record->id)->delete();
                        $invoice = EntryInvoice::find($record->id);
                        $data['total'] = 0;

                        foreach ($data['entryItems'] as $entryItem) {
                            $item = Item::where('name', $entryItem['name'])->first();
                            $data['total'] += $entryItem['total_price'];

                            if ($item) {
                                $item->unit_quantity += $entryItem['unit_quantity'];
                                $item->total_price += $entryItem['total_price'];
                                $item->save();
                            } else {
                                $item = Item::create($entryItem);
                            }

                            EntryItem::create([
                                'invoice_id' => $invoice->id,
                                'item_id' => $item->id,
                                'unit' => $entryItem['unit'],
                                'unit_price' => $entryItem['unit_price'],
                                'unit_quantity' => $entryItem['unit_quantity'],
                                'total_price' => $entryItem['total_price'],
                            ]);
                        };

                        return $invoice->update($data);
                    }),
            ])

            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntryInvoices::route('/'),
        ];
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $total = $get('unit_price') * $get('unit_quantity');
        $set('total_price',  $total);
    }
}
