<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamAttemptFactory extends Factory
{
    protected $model = ExamAttempt::class;

    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'student_id' => User::factory()->mahasiswa(),
            'started_at' => now(),
            'submitted_at' => null,
            'submission_type' => null,
            'status' => 'in_progress',
            'total_score' => null,
        ];
    }

    // ── States ─────────────────────────────────────

    public function submitted(): static
    {
        return $this->state(fn () => [
            'submitted_at' => now(),
            'submission_type' => 'manual',
            'status' => 'submitted',
        ]);
    }

    public function autoSubmitted(): static
    {
        return $this->state(fn () => [
            'submitted_at' => now(),
            'submission_type' => 'auto',
            'status' => 'submitted',
        ]);
    }

    public function graded(): static
    {
        return $this->state(fn () => [
            'submitted_at' => now(),
            'submission_type' => 'manual',
            'status' => 'graded',
            'total_score' => fake()->randomFloat(2, 50, 100),
        ]);
    }
}