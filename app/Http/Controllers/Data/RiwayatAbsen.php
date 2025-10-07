<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\RiwayatAbsen as ModelsRiwayatAbsen;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Export\Excel;
use App\Models\GuruPivot;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RiwayatAbsen extends Controller
{
    public function index()
    {
        return view('data.riwayat-absen');
    }

    public function getData(Request $request)
    {
        if (Auth::user()->guru) {
            // Anggap ini adalah guru yang sedang aktif
            $guruId = Auth::user()->guru->id;

            // Dapatkan ID Kelas dan Jurusan yang diajar oleh guru
            $teacherClasses = GuruPivot::where('guru_id', $guruId)
                ->select('kelas_id', 'jurusan_id')
                ->get();

            // Jika tidak ada data
            if ($teacherClasses->isEmpty()) {
                return collect([]); // Kembalikan koleksi kosong
            }

            // **Cara 1: Menggunakan Where Clause Kompleks (Lebih bersih jika banyak kombinasi)**
            $siswaQuery = Siswa::query();

            if (!$request->kelas || !$request->jurusan) {
                // Buat clause OR untuk setiap kombinasi kelas dan jurusan
                $siswaQuery->where(function ($query) use ($teacherClasses) {
                    foreach ($teacherClasses as $combination) {
                        $query->orWhere(function ($q) use ($combination) {
                            $q->where('kelas_id', $combination->kelas_id)
                                ->where('jurusan_id', $combination->jurusan_id);
                        });
                    }
                });
            }

            if ($request->kelas) {
                $siswaQuery->where('kelas_id', $request->kelas);
            }

            if ($request->jurusan) {
                $siswaQuery->where('jurusan_id', $request->jurusan);
            }

            $targetUserIds = $siswaQuery->pluck('user_id')->unique();

            // Ambil Riwayat Absen
            $riwayatAbsen = ModelsRiwayatAbsen::whereIn('user_id', $targetUserIds)
                ->latest()
                ->with('user')
                ->get();
        } else {
            $siswaQuery = Siswa::query();

            if ($request->kelas) {
                $siswaQuery->where('kelas_id', $request->kelas);
            }

            if ($request->jurusan) {
                $siswaQuery->where('jurusan_id', $request->jurusan);
            }

            $targetUserIds = $siswaQuery->pluck('user_id')->unique();

            $riwayatAbsen = ModelsRiwayatAbsen::whereIn('user_id', $targetUserIds)
                ->latest()
                ->with('user')
                ->get();
        }

        return DataTables::of($riwayatAbsen)
            ->addIndexColumn()
            ->addColumn('nisn', fn($row) => $row->user->siswa->nisn ?? '-')
            ->addColumn('nama', fn($row) => $row->user->siswa->name ?? '-')
            ->addColumn('jurusan', fn($row) => $row->user->siswa->jurusan->name ?? '-')
            ->addColumn('kelas', fn($row) => $row->user->siswa->kelas->name ?? '-')
            ->editColumn('created_at', fn($row) => $row->created_at->format('d M Y'))
            ->addColumn('waktu', fn($row) => $row->created_at->format('H:i:s'))
            ->addColumn('action', function ($row) {
                return $row->is_late === 'Terlambat'
                    ? '<span class="badge bg-danger">' . $row->is_late . '</span>'
                    : '<span class="badge bg-success">' . $row->is_late . '</span>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function absenExport(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'file' => 'required|in:excel,pdf',
                'tanggal-awal' => 'required|date',
                'tanggal-akhir' => 'required|date|after_or_equal:tanggal-awal'
            ]);

            $tanggalAwal = $request->input('tanggal-awal');
            $tanggalAkhir = $request->input('tanggal-akhir');
            $fileType = $request->input('file');

            if (Auth::user()->guru) {
                // Anggap ini adalah guru yang sedang aktif
                $guruId = Auth::user()->guru->id;

                // Dapatkan ID Kelas dan Jurusan yang diajar oleh guru
                $teacherClasses = GuruPivot::where('guru_id', $guruId)
                    ->select('kelas_id', 'jurusan_id')
                    ->get();

                // Jika tidak ada data
                if ($teacherClasses->isEmpty()) {
                    return collect([]); // Kembalikan koleksi kosong
                }

                // **Cara 1: Menggunakan Where Clause Kompleks (Lebih bersih jika banyak kombinasi)**
                $siswaQuery = Siswa::query();

                if (!$request->kelas || !$request->jurusan) {
                    // Buat clause OR untuk setiap kombinasi kelas dan jurusan
                    $siswaQuery->where(function ($query) use ($teacherClasses) {
                        foreach ($teacherClasses as $combination) {
                            $query->orWhere(function ($q) use ($combination) {
                                $q->where('kelas_id', $combination->kelas_id)
                                    ->where('jurusan_id', $combination->jurusan_id);
                            });
                        }
                    });
                }

                if ($request->kelas) {
                    $siswaQuery->where('kelas_id', $request->kelas);
                }

                if ($request->jurusan) {
                    $siswaQuery->where('jurusan_id', $request->jurusan);
                }

                $targetUserIds = $siswaQuery->pluck('user_id')->unique();

                // Ambil Riwayat Absen
                $data = ModelsRiwayatAbsen::whereIn('user_id', $targetUserIds)
                    ->latest()
                    ->with('user')
                    ->get();
            } else {
                $siswaQuery = Siswa::query();

                if ($request->kelas) {
                    $siswaQuery->where('kelas_id', $request->kelas);
                }

                if ($request->jurusan) {
                    $siswaQuery->where('jurusan_id', $request->jurusan);
                }

                $targetUserIds = $siswaQuery->pluck('user_id')->unique();

                $data = ModelsRiwayatAbsen::whereIn('user_id', $targetUserIds)
                    ->latest()
                    ->with('user')
                    ->get();
            }

            // Validasi jika data kosong
            if ($data->isEmpty()) {
                return back()->with('error', 'Tidak ada data absen untuk rentang tanggal yang dipilih.');
            }

            // Export berdasarkan tipe file
            if ($fileType === 'excel') {
                return $this->riwayatAbsenExcel($data, $tanggalAwal, $tanggalAkhir);
            } else if ($fileType === 'pdf') {
                return $this->exportPdf($data, $tanggalAwal, $tanggalAkhir);
            }
        } catch (Exception $e) {
            Log::error('Export Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat melakukan export: ' . $e->getMessage());
        }
    }

    public function riwayatAbsenExcel($data, $tanggalAwal, $tanggalAkhir)
    {
        $fileName = 'riwayat_absen_' . $tanggalAwal . '_sd_' . $tanggalAkhir . '.xlsx';

        return Excel::riwayatAbsen($data, $fileName);
    }

    public function exportPdf($data, $tanggalAwal, $tanggalAkhir)
    {
        $pdf = Pdf::loadView('data.export_pdf', compact('data', 'tanggalAwal', 'tanggalAkhir'))
            ->setPaper('a4', 'landscape');

        $fileName = 'riwayat_absen_' . $tanggalAwal . '_sd_' . $tanggalAkhir . '.pdf';

        return $pdf->stream($fileName);
    }
}
