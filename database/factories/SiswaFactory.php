<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nisn' => fake()->unique()->numerify('##########'),
            'jurusan_id' => fake()->numberBetween(1, 7),
            'kelas_id' => fake()->numberBetween(1, 3)
        ];
    }
}
