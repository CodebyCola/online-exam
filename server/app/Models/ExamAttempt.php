<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'submission_type',
        'status',
        'total_score',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'total_score' => 'decimal:2',
        ];
    }

    // ── Relationships ─────────────────────────────

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ExamActivityLog::class);
    }

    // ── Computed helpers ──────────────────────────
    // expires_at TIDAK disimpan sebagai kolom (turunan murni
    // dari started_at + duration, dibatasi closes_at) — dihitung
    // di sini supaya satu-satunya sumber kebenaran ada di model,
    // bukan diulang-ulang di controller/job.

    public function expiresAt(): \Carbon\Carbon
    {
        $byDuration = $this->started_at->copy()->addMinutes($this->exam->duration_minutes);

        return $byDuration->lessThan($this->exam->closes_at)
            ? $byDuration
            : $this->exam->closes_at;
    }

    public function isExpired(): bool
    {
        return now()->greaterThanOrEqualTo($this->expiresAt());
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }
}