<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'type' => 'multiple_choice',
            'content' => fake()->sentence() . '?',
            'points' => 10,
            'rubric' => null,
            'order' => 1,
        ];
    }

    // ── States ─────────────────────────────────────

    public function essay(): static
    {
        return $this->state(fn () => [
            'type' => 'essay',
            'rubric' => fake()->paragraph(),
        ]);
    }

    public function multipleChoice(): static
    {
        return $this->state(fn () => [
            'type' => 'multiple_choice',
            'rubric' => null,
        ]);
    }
}