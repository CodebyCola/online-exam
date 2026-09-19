<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_attempt_id',
        'question_id',
        'selected_option_id',
        'essay_answer',
        'score',
        'ai_suggested_score',
        'ai_feedback',
        'ai_grading_status',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'ai_suggested_score' => 'decimal:2',
        ];
    }

    // ── Relationships ─────────────────────────────

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'selected_option_id');
    }

    // ── Grading state helpers ─────────────────────

    public function hasFinalScore(): bool
    {
        return ! is_null($this->score);
    }

    public function aiGradingFailed(): bool
    {
        return $this->ai_grading_status === 'failed';
    }
}