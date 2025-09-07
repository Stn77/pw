<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatAbsen>
 */
class RiwayatAbsenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Carbon::setLocale('id');
        return [
            'user_id' => fake()->numberBetween(3, 13),
            'tanggal' => fake()->date(),
            'hari' => Carbon::parse(fake()->date())->format('l'),
            'is_late' => fake()->randomElement(['Terlambat', 'Tepat Waktu']),
        ];
    }
}
