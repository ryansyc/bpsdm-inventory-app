<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntryInvoiceResource\Pages;
use App\Filament\Resources\EntryInvoiceResource\RelationManagers;
use App\Models\Category;
use App\Models\Item;
use App\Models\EntryInvoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use App\Models\Department;
use App\Models\EntryItem;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntryInvoiceResource extends Resource
{
    protected static ?string $model = EntryInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-down-tray';

    protected static ?string $slug = 'barang-masuk';

    protected static ?string $pluralModelLabel = 'barang masuk';

    protected static ?int $navigationSort = 3;

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

                Forms\Components\TextInput::make('total')
                    ->label('Total Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->hiddenOn('create'),

                TableRepeater::make('entryItems')
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
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
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
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateTotal($get, $set);
                            }),
                        Forms\Components\TextInput::make('unit_quantity')
                            ->label('Jumlah')
                            ->required()
                            ->minValue(0)
                            ->numeric()
                            ->live(onBlur: true)
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

            ->groups(
                [
                    Group::make('category.name')
                        ->label('Kategori')
                        ->titlePrefixedWithLabel(false),
                ]
            )

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

                // Filter::make('category')
                //     ->form([
                //         Forms\Components\Select::make('category')
                //             ->label('Kategori')
                //             ->options(Category::pluck('name', 'id'))
                //             ->placeholder('-')
                //             ->required(),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['category'],
                //                 fn(Builder $query, $category): Builder => $query->where('category_id', $category),
                //             );
                //     }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
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
            ])

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
            'index' => Pages\ListEntryInvoices::route('/'),
            // 'create' => Pages\CreateEntryInvoice::route('/create'),
            // 'edit' => Pages\EditEntryInvoice::route('/{record}/edit'),
        ];
    }

    public static function calculateTotal(Get $get, Set $set): void
    {
        $total = $get('unit_price') * $get('unit_quantity');
        $set('total_price',  $total);
        // $set('total_price',  'Rp ' . number_format($total, 0, ',', '.'));
    }

    // public static function updateTotals(Get $get, Set $set): void
    // {
    //     $selectedItems = collect($get('entryItems'));

    //     $total = 0;
    //     foreach ($selectedItems as $item) {
    //         $total += $item['unit_price'] * $item['unit_quantity'] ?? 0;
    //     }

    //     $set('total', number_format($total));
    // }
}
