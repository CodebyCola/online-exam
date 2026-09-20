<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        $opensAt = now()->addDay();

        return [
            'lecturer_id' => User::factory()->dosen(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'duration_minutes' => 60,
            'opens_at' => $opensAt,
            'closes_at' => $opensAt->copy()->addHours(2),
            'late_start_cutoff_ratio' => null, // pakai default sistem
            'min_minutes_before_close' => null,
            'status' => 'draft',
        ];
    }

    // ── States ─────────────────────────────────────

    public function published(): static
    {
        return $this->state(fn () => ['status' => 'published']);
    }

    public function closed(): static
    {
        return $this->state(fn () => ['status' => 'closed']);
    }

    // Berguna untuk test/seed skenario "ujian sedang berlangsung sekarang"
    public function ongoing(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
            'opens_at' => now()->subMinutes(10),
            'closes_at' => now()->addHours(1),
        ]);
    }

    // Berguna untuk test skenario auto-submit / expired
    public function expiringSoon(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
            'opens_at' => now()->subMinutes(30),
            'closes_at' => now()->addMinute(),
            'duration_minutes' => 30,
        ]);
    }
}