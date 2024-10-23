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
            padding: 3cm;
            font-size: 11px;
            font-family: "Arial", sans-serif;
        }

        .container {
            display: table;
            width: 100%;
            height: 100%;
        }

        .content {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .table {
            border-collapse: collapse;
        }

        .table td,
        .table th {
            /* border: 1px solid black; */
            padding: 1px;
        }

        .emptyRow {
            height: 1cm;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <table class="table">
                <tbody>
                    <tr>
                        <td style="width: 3cm">DAERAH/UNIT</td>
                        <td style="width: 0.5cm; text-align: center">:</td>
                        <td style="width: 3cm">Gudang</td>
                        <td style="width: 4cm"></td>
                        <td style="width: 0.5cm; text-align: center"></td>
                        <td style="width: 3cm"></td>
                        <td style="width: 3.5cm">Model</td>
                        <td style="width: 0.5cm; text-align: center">:</td>
                        <td style="width: 3cm"></td>
                    </tr>
                    <tr>
                        <td colspan="6"></td>
                        <td class="w-40">No</td>
                        <td style="width: 0.5cm; text-align: center">:</td>
                        <td class="w-40"></td>
                    </tr>

                    <tr class="emptyRow">
                        <td colspan="9">&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="9" style="text-align: center">PEMERINTAH DAERAH PROVINSI JAWA BARAT</td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: center">GUDANG : BADAN PENGEMBANGAN SUMBER DAYA MANUSIA</td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: center">BUKTI BARANG DARI DAERAH/UNIT : SUB BAGIAN TATA USAHA</td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: center">KEPADA DAERAH/UNIT: KOORDINATOR WIDYAISWARA BPSDM JAWA BARAT</td>
                    </tr>

                    <tr class="emptyRow">
                        <td colspan="9">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="text-align: center; border: 1px solid black">Tanggal Penyerahan Barang Menurut Permintaan</td>
                        <td colspan="2" style="text-align: center; border: 1px solid black">Barang Diterima Dari Gudang</td>
                        <td colspan="2" style="text-align: center; border: 1px solid black">Nama dan Kode Barang</td>
                        <td style="text-align: center; border: 1px solid black">Satuan</td>
                        <td style="text-align: center; border: 1px solid black">Jumlah Barang</td>
                        <td colspan="2" style="text-align: center; border: 1px solid black">Jumlah Harga</td>
                    </tr>

                    @foreach ($exitInvoice->exitItems as $item)
                    <tr>
                        <td style="text-align: center; border: 1px solid black">{{ \Carbon\Carbon::parse($exitInvoice->date)->translatedFormat('d F Y') }}</td>
                        <td colspan="2" style="text-align: center; border: 1px solid black">BPSDM</td>
                        <td colspan="2" style="border: 1px solid black">{{$item->item->name}}</td>
                        <td style="text-align: center; border: 1px solid black">{{$item->item->unit}}</td>
                        <td style="text-align: center; border: 1px solid black">{{$item->unit_quantity}}</td>
                        <td colspan="2" style="text-align: center; border: 1px solid black">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach

                    <tr class="emptyRow">
                        <td colspan="9">&nbsp;</td>
                    </tr>

                    <tr>
                        <td>Daerah/Unit Umum</td>
                        <td colspan="5"></td>
                        <td colspan="3">Dibuat di Cimahi, {{ \Carbon\Carbon::parse($exitInvoice->date)->translatedFormat('d F Y') }}</td>
                    </tr>

                    <tr>
                        <td>Cimahi, {{ \Carbon\Carbon::parse($exitInvoice->date)->translatedFormat('d F Y') }}</td>
                        <td colspan="8"></td>
                    </tr>

                    <tr class="emptyRow">
                        <td colspan="9">&nbsp;</td>
                    </tr>

                    <tr>
                        <td>Yang Menerima</td>
                        <td colspan="5"></td>
                        <td colspan="3">Penyusun Kebutuhan Barang Inventaris</td>
                    </tr>

                    <tr>
                        <td>Tanda Tangan</td>
                        <td style="text-align: center">:</td>
                        <td colspan="4"></td>
                        <td>Tanda Tangan</td>
                        <td style="text-align: center">:</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Nama</td>
                        <td style="text-align: center">:</td>
                        <td>{{ strtoupper($exitInvoice->receiver) }}</td>

                        <td colspan="3"></td>
                        <td>Nama</td>
                        <td style="text-align: center">:</td>
                        <td>{{ strtoupper($exitInvoice->provider) }}</td>
                    </tr>

                    <tr>
                        <td>NIP</td>
                        <td style="text-align: center">:</td>
                        <td colspan="4"></td>
                        <td>NIP</td>
                        <td style="text-align: center">:</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Pangkat/Gol</td>
                        <td style="text-align: center">:</td>
                        <td colspan="4"></td>
                        <td>Pangkat/Gol</td>
                        <td style="text-align: center">:</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="3"></td>
                        <td colspan="3">MENGETAHUI : KEPALA SUB BAG TATA USAHA</td>
                        <td colspan="3"></td>
                    </tr>

                    <tr>
                        <td colspan="3"></td>
                        <td>Tanda Tangan</td>
                        <td style="text-align: center">:</td>
                        <td colspan="4"></td>
                    </tr>

                    <tr>
                        <td colspan="3"></td>
                        <td>Nama</td>
                        <td style="text-align: center">:</td>
                        <td colspan="4">FIRMANSYAH, S.Sos., M.SI.</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>NIP</td>
                        <td style="text-align: center">:</td>
                        <td colspan="4">197507242010011007</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>Pangkat/Gol</td>
                        <td style="text-align: center">:</td>
                        <td colspan="4">Penata Tingkat I (III/d)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>