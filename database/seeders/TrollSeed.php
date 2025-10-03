<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\RiwayatAbsen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrollSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // RiwayatAbsen::factory()->count(50)->create();
        // User::factory(5)->teacher()->has(Guru::factory())->create();

        $data = Guru::find(1);

        $kelasIds = [1,2,3];
        $jurusanIds = [1,2,3,4,5,6,7];

        foreach($kelasIds as $kelasId){
            foreach($jurusanIds as $jurusanId){
                $data->pivot()->create([
                    'kelas_id' => $kelasId,
                    'jurusan_id' => $jurusanId,
                ]);
            }
        }
    }
}
