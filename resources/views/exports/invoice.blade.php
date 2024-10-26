<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <table>
        <tbody>
            <tr>
                <td width="25">DAERAH/UNIT</td>
                <td width="2" style="text-align: center">:</td>
                <td width="20"></td>
                <td width="20"></td>
                <td width="2" style="text-align: center"></td>
                <td width="15"></td>
                <td width="20">Model</td>
                <td width="2" style="text-align: center">:</td>
                <td width="20"></td>
            </tr>
            <tr>
                <td colspan="6"></td>
                <td>No</td>
                <td style="text-align: center">:</td>
                <td></td>
            </tr>

            <tr>
                <td colspan="9"></td>
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

            <tr>
                <td colspan="9"></td>
            </tr>

            <tr>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">Tanggal Penyerahan Barang Menurut Permintaan</td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word" colspan="2">Barang Diterima Dari Gudang</td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word" colspan="2">Nama dan Kode Barang</td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">Satuan</td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">Jumlah Barang</td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word" colspan="2">Jumlah Harga</td>
            </tr>

            @foreach ($exitInvoice->items as $index => $item)
            <tr>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">@if ($index === 0){{ \Carbon\Carbon::parse($exitInvoice->date)->translatedFormat('d F Y') }}@endif</td>
                <td colspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">
                    @if ($index === 0) BPSDM @endif
                </td>
                <td colspan="2" style="border: 1px solid black; word-wrap: break-word">{{$item->item->name}}</td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">{{$item->item->unit}}</td>
                <td style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">{{$item->unit_quantity}}</td>
                <td colspan="2" style="border: 1px solid black; text-align: center; vertical-align: middle; word-wrap: break-word">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach


            <tr>
                <td colspan="9"></td>
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

            <tr>
                <td colspan="9"></td>
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
                <td colspan="4">{{ strtoupper($exitInvoice->receiver) }}</td>
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
                <td colspan="9"></td>
            </tr>

            <tr>
                <td colspan="3"></td>
                <td colspan="6">MENGETAHUI : KEPALA SUB BAG TATA USAHA</td>
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
                <td style="text-align: left" colspan="4">197507242010011007&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>Pangkat/Gol</td>
                <td style="text-align: center">:</td>
                <td colspan="4">Penata Tingkat I (III/d)</td>
            </tr>
        </tbody>
    </table>
</body>

</html>