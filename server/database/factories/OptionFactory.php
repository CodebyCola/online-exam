<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    protected $model = Option::class;

    public function definition(): array
    {
        return [
            'question_id' => Question::factory(),
            'content' => fake()->sentence(3),
            'is_correct' => false,
        ];
    }

    // ── States ─────────────────────────────────────

    public function correct(): static
    {
        return $this->state(fn () => ['is_correct' => true]);
    }
}