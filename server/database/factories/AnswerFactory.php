<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\ExamAttempt;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    public function definition(): array
    {
        return [
            'exam_attempt_id' => ExamAttempt::factory(),
            'question_id' => Question::factory(),
            'selected_option_id' => null,
            'essay_answer' => null,
            'score' => null,
            'ai_suggested_score' => null,
            'ai_feedback' => null,
            'ai_grading_status' => null,
        ];
    }

    // ── States ─────────────────────────────────────

    public function essay(): static
    {
        return $this->state(fn () => [
            'selected_option_id' => null,
            'essay_answer' => fake()->paragraph(),
        ]);
    }

    public function essayGraded(): static
    {
        return $this->state(fn () => [
            'ai_suggested_score' => fake()->randomFloat(2, 5, 10),
            'ai_feedback' => fake()->sentence(),
            'ai_grading_status' => 'success',
            'score' => fake()->randomFloat(2, 5, 10), // sudah di-approve dosen
        ]);
    }

    public function essayGradingFailed(): static
    {
        return $this->state(fn () => [
            'ai_grading_status' => 'failed',
            'ai_feedback' => null,
        ]);
    }
}