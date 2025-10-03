<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\RiwayatAbsen;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $jurusan = [
            'mp',
            'ak',
            'bd',
            'tsm',
            'dkv',
            'pplg',
            'tkkr'
        ];

        $kelas = [
            'X',
            'XI',
            'XII',
        ];

        foreach ($jurusan as $j) {
            Jurusan::create(['name' => $j]);
        }

        foreach ($kelas as $k) {
            Kelas::create(['name' => $k]);
        }

        $this->call([
            AccountSeeder::class,
            TrollSeed::class
        ]);

        RiwayatAbsen::factory()->count(50)->create();
    }
}
