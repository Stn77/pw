<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Riwayat Absen</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: center; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Laporan Riwayat Absen</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Jurusan</th>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $absen)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $absen->user->siswa->nisn ?? '-' }}</td>
                    <td>{{ $absen->user->siswa->name ?? '-' }}</td>
                    <td>{{ $absen->user->siswa->jurusan->name ?? '-' }}</td>
                    <td>{{ $absen->user->siswa->kelas->name ?? '-' }}</td>
                    <td>{{ $absen->hari }}</td>
                    <td>{{ $absen->created_at->format('d M Y') }}</td>
                    <td>{{ $absen->created_at->format('H:i:s') }}</td>
                    <td>{{ $absen->is_late }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
