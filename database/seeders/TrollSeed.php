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
        User::factory(5)->teacher()->has(Guru::factory())->create();
    }
}
