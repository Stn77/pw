<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);
        $scanner = Role::create(['name' => 'scanner']);
        $teacher = Role::create(['name' => 'teacher']);

        User::create([
            'name' => 'Scanner',
            'email' => 'scanner@example.com',
            'password' => bcrypt('123'),
            'username' => 'scanner'
        ])->assignRole($scanner);

        User::create([
            'name' => 'Teacher',
            'email' => 'teacher@example.com',
            'password' => bcrypt('123'),
            'username' => 'teacher'
        ])->assignRole($teacher);

        User::create([
            'name' => 'John Doe',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
            'username' => 'admin',
        ])->assignRole($admin);

        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('123'),
            'username' => 'user1'
        ])->assignRole($user);
    }
}
