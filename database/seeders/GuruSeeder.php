<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(5)->teacher()->has(Guru::factory())->create();

        User::create([
            'name' => 'Guru xpplg',
            'password' => bcrypt('123'),
            'email' => 'guruxpplg@example.com',
            'username' => 'guruxpplg'
        ])->assignRole('teacher')->guru()->create([
            'name' => 'Guru xpplg',
            'nip' => '1234567890'
        ]);
    }
}
