<?php

namespace App\Import;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ExcelImport
{
    public function importGuru($filePath)
    {
        try {
            // Load spreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $header = array_shift($rows);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index 0 dan header

                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Validasi data
                    if (count($row) < 5) {
                        $errors[] = "Baris {$rowNumber}: Data tidak lengkap";
                        $errorCount++;
                        continue;
                    }

                    $nip = trim($row[0]);
                    $username = trim($row[1]);
                    $namaLengkap = trim($row[2]);
                    $email = trim($row[3]);
                    $password = trim($row[4]);

                    // Validasi field tidak boleh kosong
                    if (empty($username) || empty($namaLengkap) || empty($password) || empty($nip)) {
                        $errors[] = "Baris {$rowNumber}: Ada field yang kosong";
                        $errorCount++;
                        continue;
                    }

                    // Validasi format email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Baris {$rowNumber}: Format email tidak valid ({$email})";
                        $errorCount++;
                        continue;
                    }

                    // Password minimal 6 karakter
                    if(strlen($password) < 6){
                        $errors[] = "Baris {$rowNumber}: Password minimal 6 karakter";
                        $errorCount++;
                        continue;
                    }

                    // NIP harus angka dan minimal 10 digit
                    if(empty($nip) || (!is_numeric($nip) || strlen($nip) < 10)){
                        $errors[] = "Baris {$rowNumber}: NIP harus berupa angka dan minimal 10 digit";
                        $errorCount++;
                        continue;
                    }

                    // nama lengkap minimal 3 karakter
                    if(empty($namaLengkap) || strlen($namaLengkap) < 3){
                        $errors[] = "Baris {$rowNumber}: Nama lengkap minimal 3 karakter";
                        $errorCount++;
                        continue;
                    }

                    // Cek duplikat username
                    if (User::where('username', $username)->exists()) {
                        $errors[] = "Baris {$rowNumber}: Username '{$username}' sudah ada";
                        $errorCount++;
                        continue;
                    }

                    // Cek duplikat email
                    if (User::where('email', $email)->exists()) {
                        $errors[] = "Baris {$rowNumber}: Email '{$email}' sudah ada";
                        $errorCount++;
                        continue;
                    }

                    // Insert user
                    User::create([
                        'username' => $username,
                        'name' => $namaLengkap,
                        'email' => $email,
                        'password' => $password, // Akan di-hash otomatis oleh model
                    ])->assignRole('teacher')->guru()->create([
                        'name' => $namaLengkap,
                        'nip' => $nip
                    ]);

                    $successCount++;

                } catch (Exception $e) {
                    $errors[] = "Baris {$rowNumber}: Error - " . $e->getMessage();
                    $errorCount++;
                    Log::error("Import error on row {$rowNumber}: " . $e->getMessage());
                }
            }

            DB::commit();

            return [
                'success' => true,
                'total_processed' => $successCount + $errorCount,
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ];

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Excel import failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal mengimpor file: ' . $e->getMessage(),
                'errors' => []
            ];
        }
    }

    public function importSiswa($filePath)
    {
        try{
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $header = array_shift($rows);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach($rows as $index => $row){
                $rowNumber = $index + 2;

                try{
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Validasi data
                    if (count($row) < 7) {
                        $errors[] = "Baris {$rowNumber}: Data tidak lengkap";
                        $errorCount++;
                        continue;
                    }

                    $nisn = trim($row[0]);
                    $namaLengkap = trim($row[1]);
                    $username = trim($row[2]);
                    $email = trim($row[3]);
                    $password = trim($row[4]);
                    $kelas = trim($row[5]);
                    $jurusan = trim($row[6]);

                    // Validasi field tidak boleh kosong
                    if (empty($username) || empty($namaLengkap) || empty($password) || empty($nisn) || empty($kelas) || empty($jurusan)) {
                        $errors[] = "Baris {$rowNumber}: Ada field yang kosong";
                        $errorCount++;
                        continue;
                    }

                    if(!in_array($kelas, ['1','2','3'])){
                        $errors[] = "Baris {$rowNumber}: Kelas harus diisi dengan angka 1, 2, atau 3 sesuai format";
                        $errorCount++;
                        continue;
                    }

                    if(!in_array($jurusan, ['1','2','3','4','5','6','7'])){
                        $errors[] = "Baris {$rowNumber}: Jurusan harus diisi dengan angka 1, 2, 3, 4, 5, 6, atau 7 sesuai format";
                        $errorCount++;
                        continue;
                    }

                    // Validasi format email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Baris {$rowNumber}: Format email tidak valid ({$email})";
                        $errorCount++;
                        continue;
                    }

                    // Password minimal 6 karakter
                    if(strlen($password) < 6){
                        $errors[] = "Baris {$rowNumber}: Password minimal 6 karakter";
                        $errorCount++;
                        continue;
                    }

                    // NISN harus angka dan minimal 5 digit
                    if(empty($nisn) || (!is_numeric($nisn) || strlen($nisn) < 5)){
                        $errors[] = "Baris {$rowNumber}: NISN harus berupa angka dan minimal 5 digit";
                        $errorCount++;
                        continue;
                    }

                    // nama lengkap minimal 3 karakter
                    if(empty($namaLengkap) || strlen($namaLengkap) < 3){
                        $errors[] = "Baris {$rowNumber}: Nama lengkap minimal 3 karakter";
                        $errorCount++;
                        continue;
                    }

                    // Cek duplikat username
                    if (User::where('username', $username)->exists()) {
                        $errors[] = "Baris {$rowNumber}: Username '{$username}' sudah ada";
                        $errorCount++;
                        continue;
                    }

                    // Cek duplikat email
                    if (User::where('email', $email)->exists()) {
                        $errors[] = "Baris {$rowNumber}: Email '{$email}' sudah ada";
                        $errorCount++;
                        continue;
                    }

                    // Insert user
                    User::create([
                        'username' => $username,
                        'name' => $namaLengkap,
                        'email' => $email,
                        'password' => $password, // Akan di-hash otomatis oleh model
                    ])->assignRole('user')->siswa()->create([
                        'name' => $namaLengkap,
                        'nisn' => $nisn,
                        'kelas_id' => $kelas,
                        'jurusan_id' => $jurusan
                    ]);

                    $successCount++;

                }catch(Exception $e){
                    $errors[] = "Baris {$rowNumber}: Error - " . $e->getMessage();
                    $errorCount++;
                    Log::error("Import error on row {$rowNumber}: " . $e->getMessage());
                }
            }

            DB::commit();

            Log::info("Import completed: {$successCount} success, {$errorCount} errors");
            Log::debug("Import details: ", ['errors' => $errors]);
            return [
                'success' => true,
                'total_processed' => $successCount + $errorCount,
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ];
        }catch(Exception $e){
            DB::rollBack();
            Log::error('Excel import failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal mengimpor file: ' . $e->getMessage(),
                'errors' => []
            ];
        }
    }
}
