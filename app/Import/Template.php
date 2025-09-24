<?php

namespace App\Import;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Template
{
    public function getTemplateGuru()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama Lengkap Guru');
        $sheet->setCellValue('D1', 'Email Guru (Opsional)');
        $sheet->setCellValue('E1', 'Password');

        // Style header
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

        // Set column widths
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // start download file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_input_data_guru'. Carbon::now()->format('d-m-Y H:i:s') .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    
    public function getTemplateSiswa()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'Nama Lengkap Siswa');
        $sheet->setCellValue('C1', 'Username Siswa');
        $sheet->setCellValue('D1', 'Email Siswa (Opsional)');
        $sheet->setCellValue('E1', 'Password');
        $sheet->setCellValue('F1', 'Kelas');
        $sheet->setCellValue('G1', 'Jurusan');

        // Style header
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Set column widths
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->getColumnDimension('J')->setWidth(30);
        $sheet->getColumnDimension('K')->setWidth(30);
        $sheet->getColumnDimension('L')->setWidth(30);
        $sheet->getColumnDimension('M')->setWidth(30);

        $sheet->setCellValue('J1', 'FORMAT PENULISAN KELAS DAN JURUSAN YANG BENAR');
        $sheet->setCellValue('J2', 'Penulisan ini bertujuan supaya data dapat diimport dengan benar, dan tidak terjadi error pada sistem saaat dalam proses import data');
        $sheet->setCellValue('J3', 'Jurusan yang tersedia :');
        $sheet->setCellValue('J4', 'MP');
        $sheet->setCellValue('J5', 'AK');
        $sheet->setCellValue('J6', 'BD');
        $sheet->setCellValue('J7', 'TSM');
        $sheet->setCellValue('J8', 'DKV');
        $sheet->setCellValue('J9', 'PPLG');
        $sheet->setCellValue('J10', 'TKKR');
        $sheet->setCellValue('K3', 'Id Penulisan Jurusan :');
        $sheet->setCellValue('K4', '1');
        $sheet->setCellValue('K5', '2');
        $sheet->setCellValue('K6', '3');
        $sheet->setCellValue('K7', '4');
        $sheet->setCellValue('K8', '5');
        $sheet->setCellValue('K9', '6');
        $sheet->setCellValue('K10', '7');
        $sheet->getStyle('K4:K10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->setCellValue('L3', 'Kelas yang tersedia :');
        $sheet->setCellValue('L4', 'X');
        $sheet->setCellValue('L5', 'XI');
        $sheet->setCellValue('L6', 'XII');
        $sheet->setCellValue('M3', 'Id Penulisan Kelas :');
        $sheet->setCellValue('M4', '1');
        $sheet->setCellValue('M5', '2');
        $sheet->setCellValue('M6', '3');
        $sheet->getStyle('M4:M6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->mergeCells('J1:M1');
        $sheet->mergeCells('J2:M2');
        $sheet->getStyle('J1:M1')->getFont()->setBold(true);
        $sheet->getStyle('J1:M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J1:M1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('J2')->getAlignment()->setWrapText(true);

        $sheet->setCellValue('K12', 'Contoh Pengisian Kelas dan Jurusan');
        $sheet->mergeCells('K12:L12');
        $sheet->getStyle('K12:L12')->getFont()->setBold(true);
        $sheet->getStyle('K12:L12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K12:L12')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('J13', 'data kelas harus ditulis dengan id nya. semisal kelas X ditulis dengan 1,  kelas XI ditulis dengan 2 dst');
        $sheet->setCellValue('J14', 'data jurusan harus ditulis dengan id nya. semisal jurusan MP ditulis dengan 1,  jurusan AK ditulis dengan 2 dst');

        $sheet->mergeCells('J13:M13');
        $sheet->mergeCells('J14:M14');
        $sheet->getStyle('J13:M14')->getAlignment()->setWrapText(true);
        $sheet->getStyle('J13:M14')->getFont()->setItalic(true);

        $sheet->setCellValue('J16', 'Penulisan Salah: ');
        $sheet->setCellValue('L16', 'Penulisan Benar: ');
        $sheet->setCellValue('J17', 'Kelas');
        $sheet->setCellValue('K17', 'Jurusan');
        $sheet->setCellValue('L17', 'Kelas');
        $sheet->setCellValue('M17', 'Jurusan');

        $sheet->setCellValue('J18', 'X');
        $sheet->setCellValue('J19', 'XII');
        $sheet->setCellValue('J20', 'X');
        $sheet->setCellValue('J21', 'XI');

        $sheet->setCellValue('K18', 'TKKR');
        $sheet->setCellValue('K19', 'MP');
        $sheet->setCellValue('K20', 'BD');
        $sheet->setCellValue('K21', 'TKKR');

        $sheet->setCellValue('L18', '1');
        $sheet->setCellValue('L19', '3');
        $sheet->setCellValue('L20', '1');
        $sheet->setCellValue('L21', '2');

        $sheet->setCellValue('M18', '7');
        $sheet->setCellValue('M19', '1');
        $sheet->setCellValue('M20', '3');
        $sheet->setCellValue('M21', '7');

        $sheet->setCellValue('J23', 'Jika Sudah Terlanjur Mengisi Dengan Penulisan Yang Salah: ');
        $sheet->setCellValue('J24', '1. Tekan Tombol CTRL + H pada keyboard');
        $sheet->setCellValue('J25', '2. Pada kolom "Find what" isi dengan penulisan yang salah');
        $sheet->setCellValue('J26', '3. Pada kolom "Replace with" isi dengan penulisan yang benar sesuai tabel diatas');
        $sheet->setCellValue('J27', '4. Klik tombol "Replace All"');
        $sheet->setCellValue('J28', '5. Simpan file excel, dan import kembali ke sistem');

        $sheet->mergeCells('J23:M23');
        $sheet->mergeCells('J24:M24');
        $sheet->mergeCells('J25:M25');
        $sheet->mergeCells('J26:M26');
        $sheet->mergeCells('J27:M27');
        $sheet->mergeCells('J28:M28');
        $sheet->getStyle('J23:M28')->getAlignment()->setWrapText(true);

        $sheet->getStyle('J16:M17')->getFont()->setBold(true);
        $sheet->getStyle('J23')->getFont()->setBold(true);
        $sheet->getStyle('J16:M17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J16:M17')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('J23:M28')->getFont()->setItalic(true);
        $sheet->getStyle('J23:M28');

        $sheet->setCellValue('J31', 'Hapus Seluruh Tabel Ini Saat Akan Mengimport Data, agar tidak terjadi error');
        $sheet->mergeCells('J31:M31');
        $sheet->getStyle('J31:M31')->getFont()->setBold(true);
        $sheet->getStyle('J31:M31')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J31:M31')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ]
            ]
        ];

        $sheet->getStyle('J1:M10')->applyFromArray($borderStyle);
        $sheet->getStyle('J12:M21')->applyFromArray($borderStyle);
        $sheet->getStyle('J23:M28')->applyFromArray($borderStyle);

        // start download file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_input_data_siswa'. Carbon::now()->format('d-m-Y H:i:s') .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
