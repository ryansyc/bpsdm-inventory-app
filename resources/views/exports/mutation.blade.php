<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <table>
        <tbody style="font-family: Arial; font-size: 8px;">
            <tr>
                <td width="4"></td>
                <td width="11"></td>
                <td width="6"></td>
                <td width="12"></td>
                <td width="11"></td>
                <td width="6"></td>
                <td width="12"></td>
                <td width="11"></td>
                <td width="6"></td>
                <td width="12"></td>
                <td width="11"></td>
                <td width="6"></td>
                <td width="12"></td>
                <td width="11"></td>
            </tr>
            <tr>
                <td colspan="14" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align:middle">MUTASI PERSEDIAAN</td>
            </tr>
            <tr>
                <td colspan="14" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align:middle">TAHUN ANGGARAN {{ now()->year }}</td>
            </tr>
            <tr>
                <td colspan="14" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align:middle">SKPD : BADAN PENGEMBANGAN SUMBER DAYA MANUSIA PROVINSI JAWA BARAT</td>
            </tr>

            <tr>
                <td bgcolor="#ACD7E6" rowspan="3" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">NO</td>
                <td bgcolor="#ACD7E6" rowspan="3" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">NAMA BARANG</td>
                <td bgcolor="#ACD7E6" colspan="3" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">SALDO AWAL PER</td>
                <td bgcolor="#ACD7E6" colspan="3" rowspan="2" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">PEMBELIAN {{ strtoupper(now()->translatedFormat('F')) }}</td>
                <td bgcolor="#ACD7E6" colspan="3" rowspan="2" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">PEMAKAIAN {{ strtoupper(now()->translatedFormat('F')) }}</td>
                <td bgcolor="#ACD7E6" colspan="3" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">SALDO AKHIR PER</td>
            </tr>

            <tr>
                <td bgcolor="#ACD7E6" colspan="3" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">{{ strtoupper(now()->startOfMonth()->translatedFormat('j F Y')) }}</td>
                <td bgcolor="#ACD7E6" colspan="3" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">{{ strtoupper(now()->endOfMonth()->translatedFormat('j F Y')) }}</td>
            </tr>

            <tr>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">UNIT</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">HARGA SATUAN</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">JUMLAH</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">UNIT</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">HARGA SATUAN</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">JUMLAH</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">UNIT</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">HARGA SATUAN</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">JUMLAH</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">UNIT</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">HARGA SATUAN</td>
                <td bgcolor="#ACD7E6" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">JUMLAH</td>
            </tr>

            <tr>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">JUMLAH</td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle">{{ number_format($categoryTotal['start_total'], 2, ',', '.') }}</td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle">{{ number_format($categoryTotal['entry_total'], 2, ',', '.') }}</td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle">{{ number_format($categoryTotal['exit_total'], 2, ',', '.') }}</td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle"></td>
                <td bgcolor="#59CC9D" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle">{{ number_format($categoryTotal['final_total'], 2, ',', '.') }}</td>
            </tr>

            @foreach ($rowCategories as $rowCategory)
            <tr>
                <td height="20" bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: center; vertical-align: middle">{{ toRoman($loop->iteration) }}</td>
                <td bgcolor="#F2CC4E" colspan="3" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: left; vertical-align: middle; word-wrap: break-word">{{ $rowCategory->name }}</td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"> {{ number_format($rowCategory->start_total, 2, ',', '.') }}</td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"></td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"></td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle">
                    {{ number_format($rowCategory->entry_total, 2, ',', '.') }}
                </td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"></td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"></td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle">
                    {{ number_format($rowCategory->exit_total, 2, ',', '.') }}
                </td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"></td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"></td>
                <td bgcolor="#F2CC4E" style="border: 1px solid black; font-family: Arial; font-size: 8px; font-weight: bold; text-align: right; vertical-align: middle"> {{ number_format($rowCategory->final_total, 2, ',', '.') }}</td>
            </tr>
            @foreach ($rowCategory->start_stocks as $startStock)
            @php
            $entryItem = $rowCategory->entry_items->firstWhere('item_id', $startStock->id);
            $exitItem = $rowCategory->exit_items->firstWhere('item_id', $startStock->id);
            $finalStock = $rowCategory->final_stocks->firstWhere('id', $startStock->id);
            @endphp
            <tr>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: left; vertical-align: middle; word-wrap: break-word">{{ $startStock->name }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle">{{ $startStock->unit_quantity }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($startStock->unit_price, 2, ',', '.') }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($startStock->total_price, 2, ',', '.') }}</td>
                @if ($entryItem)
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle">{{ $entryItem->unit_quantity  }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($entryItem->unit_price, 2, ',', '.') }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($entryItem->total_price, 2, ',', '.') }}</td>
                @else
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle"></td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle"></td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle"></td>
                @endif
                @if ($exitItem)
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle">{{ $exitItem->unit_quantity }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($exitItem->unit_price, 2, ',', '.') }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($exitItem->total_price, 2, ',', '.') }}</td>
                @else
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle"></td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle"></td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle"></td>
                @endif
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: center; vertical-align: middle">{{ $finalStock->unit_quantity }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($finalStock->unit_price, 2, ',', '.') }}</td>
                <td style="border: 1px solid black; font-family: Arial; font-size: 8px; text-align: right; vertical-align: middle">{{ number_format($finalStock->total_price, 2, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
                <td style="border: 1px solid black"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

@php
function toRoman($number)
{
$map = [
4000 => 'MMMM',
3000 => 'MMM',
2000 => 'MM',
1000 => 'M',
900 => 'CM',
500 => 'D',
400 => 'CD',
300 => 'CCC',
200 => 'CC',
100 => 'C',
90 => 'XC',
50 => 'L',
40 => 'XL',
30 => 'XXX',
20 => 'XX',
10 => 'X',
9 => 'IX',
5 => 'V',
4 => 'IV',
3 => 'III',
2 => 'II',
1 => 'I',
];

$roman = '';

foreach ($map as $int => $rom) {
while ($number >= $int) {
$roman .= $rom;
$number -= $int;
}
}

return $roman;
}
@endphp

</html>