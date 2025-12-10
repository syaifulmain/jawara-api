<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .periode {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<h2>{{ $judul }}</h2>
<p class="periode">Periode: {{ $periode['text'] }}</p>

<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Jenis</th>
        <th>Kategori</th>
        <th>Deskripsi</th>
        <th class="text-right">Jumlah</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transaksi as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item['tanggal'] }}</td>
            <td>{{ $item['jenis'] }}</td>
            <td>{{ $item['kategori'] }}</td>
            <td>{{ $item['deskripsi'] }}</td>
            <td class="text-right">{{ $item['formatted_jumlah'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table>
    <tr class="total-row">
        <td>Total Pemasukan</td>
        <td class="text-right">{{ $ringkasan['formatted_total_pemasukan'] }}</td>
    </tr>
    <tr class="total-row">
        <td>Total Pengeluaran</td>
        <td class="text-right">{{ $ringkasan['formatted_total_pengeluaran'] }}</td>
    </tr>
    <tr class="total-row">
        <td>Saldo Akhir</td>
        <td class="text-right">{{ $ringkasan['formatted_saldo_akhir'] }}</td>
    </tr>
</table>

<p>Dicetak pada: {{ $dicetak_pada }}</p>
</body>
</html>
