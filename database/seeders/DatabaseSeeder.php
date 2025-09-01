<?php

namespace Database\Seeders;

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

        User::create([
            'name' => 'John Doe',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
            'username' => 'admin',
        ]);
    }
}
