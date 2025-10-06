<?php

namespace App\Export;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Excel
{
    public static function riwayatAbsen($data, $fileName)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

                $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C7C7C7']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];

        // Auto width kolom
        foreach (range('A', 'O') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'Nama Siswa');
        $sheet->setCellValue('D1', 'Jurusan');
        $sheet->setCellValue('E1', 'Kelas');
        $sheet->setCellValue('F1', 'Hari');
        $sheet->setCellValue('G1', 'Tanggal');
        $sheet->setCellValue('H1', 'Waktu');
        $sheet->setCellValue('I1', 'Keterangan');

        // Isi data.
        $row = 2;
        $no = 1;
        foreach ($data as $absen) {
            $sheet->setCellValue("A$row", $no++);
            $sheet->setCellValue("B$row", $absen->user->siswa->nisn ?? '-');
            $sheet->setCellValue("C$row", $absen->user->siswa->name ?? '-');
            $sheet->setCellValue("D$row", $absen->user->siswa->jurusan->name ?? '-');
            $sheet->setCellValue("E$row", $absen->user->siswa->kelas->name ?? '-');
            $sheet->setCellValue("F$row", $absen->hari);
            $sheet->setCellValue("G$row", $absen->created_at->format('d M Y'));
            $sheet->setCellValue("H$row", $absen->created_at->format('H:i:s'));
            $sheet->setCellValue("I$row", $absen->is_late);
            $row++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path("app/public/{$fileName}");
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
