<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\RiwayatAbsen as ModelsRiwayatAbsen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Export\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class RiwayatAbsen extends Controller
{
    public function index()
    {
        return view('data.riwayat-absen');
    }

    public function getData()
    {
        $data = ModelsRiwayatAbsen::with('user.siswa.kelas', 'user.siswa.jurusan')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nisn', fn($row) => $row->user->siswa->nisn ?? '-')
            ->addColumn('nama', fn($row) => $row->user->siswa->name ?? '-')
            ->addColumn('jurusan', fn($row) => $row->user->siswa->jurusan->name ?? '-')
            ->addColumn('kelas', fn($row) => $row->user->siswa->kelas->name ?? '-')
            ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))
            ->addColumn('waktu', fn($row) => $row->created_at->format('H:i:s'))
            ->addColumn('action', function($row) {
                return $row->is_late === 'Terlambat'
                    ? '<span class="badge bg-danger">'.$row->is_late.'</span>'
                    : '<span class="badge bg-success">'.$row->is_late.'</span>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function riwayatAbsenExcel()
    {
        $data = ModelsRiwayatAbsen::with('user.siswa.kelas', 'user.siswa.jurusan')->get();
        return Excel::riwayatAbsen($data);
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $query = ModelsRiwayatAbsen::with('user.siswa.kelas', 'user.siswa.jurusan');

        if ($tanggalAwal && $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('data.export_pdf', compact('data'))
            ->setPaper('a4', 'landscape');
            // dd($data);

        $fileName = 'riwayat_absen_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->stream($fileName);

    }

}
