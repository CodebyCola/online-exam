<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'type',
        'content',
        'points',
        'rubric',
        'order',
    ];

    // ── Relationships ─────────────────────────────

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function correctOption(): HasMany
    {
        // dipakai internal grading, JANGAN pernah expose ke API Resource
        // untuk role mahasiswa
        return $this->hasMany(Option::class)->where('is_correct', true);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    // ── Type helpers ──────────────────────────────

    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple_choice';
    }

    public function isEssay(): bool
    {
        return $this->type === 'essay';
    }
}