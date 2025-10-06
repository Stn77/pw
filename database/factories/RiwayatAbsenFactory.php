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
        $tanggal = fake()->dateTimeBetween('2025-9-30', '2025-10-3');
        Carbon::setLocale('id');
        return [
            'user_id' => fake()->numberBetween(3, 53),
            'tanggal' => $tanggal,
            'hari' => $tanggal,
            'is_late' => fake()->randomElement(['Terlambat', 'Tepat Waktu']),
        ];
    }
}
