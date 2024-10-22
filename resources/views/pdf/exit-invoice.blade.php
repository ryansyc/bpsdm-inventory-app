<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            size: 29.7cm 21cm;
            margin: 0;
        }

        body {
            padding: .5in;
            font-size: 8px;
        }

        .table {
            border-collapse: collapse;
        }

        .table td,
        .table th {
            border: 1px solid black;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .emptyRow {
            height: 0.5cm;
        }
    </style>
</head>

<body>
    <div style="display: flex; justify-content: center; padding: 10px;">
        <table class="table">
            <tbody>
                <tr>
                    <td style="width: 3cm">DAERAH/UNIT</td>
                    <td style="width: 0.5cm; text-align: center">:</td>
                    <td style="width: 3cm">Gudang</td>
                    <td style="width: 4cm"></td>
                    <td style="width: 0.5cm; text-align: center"></td>
                    <td style="width: 3cm"></td>
                    <td style="width: 3cm">Model</td>
                    <td style="width: 0.5cm; text-align: center">:</td>
                    <td style="width: 3cm"></td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td class="w-40">No</td>
                    <td style="width: 0.5cm" style="text-align: center">:</td>
                    <td class="w-40"></td>
                </tr>

                <tr class="emptyRow"></tr>

                <tr>
                    <td colspan="9">PEMERINTAH DAERAH PROVINSI JAWA BARAT</td>
                </tr>
                <tr>
                    <td colspan="9">GUDANG : BADAN PENGEMBANGAN SUMBER DAYA MANUSIA</td>
                </tr>
                <tr>
                    <td colspan="9">BUKTI BARANG DARI DAERAH/UNIT : SUB BAGIAN TATA USAHA</td>
                </tr>
                <tr>
                    <td colspan="9">KEPADA DAERAH/UNIT: KOORDINATOR WIDYAISWARA BPSDM JAWA BARAT</td>
                </tr>

                <tr class="emptyRow"></tr>

                <tr>
                    <td>Tanggal Penyerahan Barang Menurut Permintaan</td>
                    <td colspan="2">Barang Diterima Dari Gudang</td>
                    <td colspan="2">Nama dan Kode Barang</td>
                    <td>Satuan</td>
                    <td>Jumlah Barang</td>
                    <td colspan="2">Jumlah Harga</td>
                </tr>

                @foreach ($exitInvoice->exitItems as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($exitInvoice->date)->translatedFormat('d F Y') }}</td>
                    <td colspan="2">BPSDM</td>
                    <td colspan="2">{{$item->item->name}}</td>
                    <td>{{$item->item->unit}}</td>
                    <td>{{$item->unit_quantity}}</td>
                    <td colspan="2">{{$item->total_price}}</td>
                </tr>
                @endforeach

                <tr class="emptyRow"></tr>

                <tr>
                    <td>Daerah/Unit Umum</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">Dibuat di Cimahi, {{ \Carbon\Carbon::parse($exitInvoice->date)->translatedFormat('d F Y') }}</td>
                    <td></td>
                    <td></td>
            </tbody>
        </table>
    </div>
</body>

</html>