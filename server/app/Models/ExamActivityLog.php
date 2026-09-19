<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamActivityLog extends Model
{
    use HasFactory;

    public $timestamps = false; // hanya created_at, sesuai desain schema

    protected $fillable = [
        'exam_attempt_id',
        'event_type',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->created_at = $log->created_at ?? now();
        });
    }

    // ── Relationships ─────────────────────────────

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }
}