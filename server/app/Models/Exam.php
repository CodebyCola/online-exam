<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'title',
        'description',
        'duration_minutes',
        'opens_at',
        'closes_at',
        'late_start_cutoff_ratio',
        'min_minutes_before_close',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'opens_at' => 'datetime',
            'closes_at' => 'datetime',
            'late_start_cutoff_ratio' => 'decimal:2',
        ];
    }

    // ── Domain constant ───────────────────────────
    // Default rasio cutoff kalau dosen gak set sendiri.
    // Ditaruh di sini (bukan hardcode di Service) supaya
    // gampang ditemukan & diubah dari satu tempat.

    public const DEFAULT_LATE_START_CUTOFF_RATIO = 0.5;

    // ── Relationships ─────────────────────────────

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    // ── Computed helpers ──────────────────────────
    // Logic yang kita bahas panjang lebar sebelumnya
    // (last_start_at, dsb) hidup DI SINI, bukan di controller,
    // supaya bisa dipakai ulang dari mana saja & gampang ditest.

    public function lastStartAt(): \Carbon\Carbon
    {
        $ratio = $this->late_start_cutoff_ratio
            ?? self::DEFAULT_LATE_START_CUTOFF_RATIO;

        $windowSeconds = $this->opens_at->diffInSeconds($this->closes_at);

        return $this->opens_at->copy()->addSeconds((int) ($windowSeconds * $ratio));
    }

    public function isPastLateStartCutoff(): bool
    {
        return now()->greaterThan($this->lastStartAt());
    }
}