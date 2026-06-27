<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ ucfirst($jenis) }}</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 40px; }
        h1, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f4f4f4; }
        .text-right { text-align: right; }
        .footer { margin-top: 50px; text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Document</button>
    </div>
    
    <h1>PERPUSTAKAAN UMUM KOTA SURABAYA</h1>
    <h3>LAPORAN {{ strtoupper($jenis) }}</h3>
    <p style="text-align: center; color: #666;">Periode: {{ \Carbon\Carbon::parse($tgl_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tgl_akhir)->format('d M Y') }}</p>

    <table>
        <thead>
            @if($jenis == 'peminjaman')
            <tr>
                <th>ID</th>
                <th>Tanggal Pinjam</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Status</th>
            </tr>
            @else
            <tr>
                <th>ID Denda</th>
                <th>Tanggal Kembali</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Keterlambatan</th>
                <th class="text-right">Total Denda</th>
                <th>Status</th>
            </tr>
            @endif
        </thead>
        <tbody>
            @if($jenis == 'peminjaman')
                @foreach($data as $row)
                <tr>
                    <td>#{{ $row->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tgl_pinjam)->format('d/m/Y') }}</td>
                    <td>{{ $row->anggota->nama_lengkap ?? '-' }}</td>
                    <td>{{ $row->buku->judul ?? '-' }}</td>
                    <td>{{ strtoupper($row->status) }}</td>
                </tr>
                @endforeach
            @else
                @foreach($data as $row)
                <tr>
                    <td>#{{ $row->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->peminjaman->tgl_kembali)->format('d/m/Y') }}</td>
                    <td>{{ $row->peminjaman->anggota->nama_lengkap ?? '-' }}</td>
                    <td>{{ $row->peminjaman->buku->judul ?? '-' }}</td>
                    <td>{{ $row->hari_terlambat }} Hari</td>
                    <td class="text-right">Rp {{ number_format($row->total_denda, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($row->status_bayar) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-right"><strong>TOTAL KESELURUHAN</strong></td>
                    <td colspan="2"><strong>Rp {{ number_format($data->sum('total_denda'), 0, ',', '.') }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Surabaya, {{ now()->format('d F Y') }}</p>
        <br><br><br>
        <p>_______________________</p>
        <p>Admin Perpustakaan</p>
    </div>
</body>
</html>
