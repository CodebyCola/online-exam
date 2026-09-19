<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions');
            $table->foreignId('selected_option_id')->nullable()->constrained('options');
            $table->text('essay_answer')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->decimal('ai_suggested_score', 5, 2)->nullable();
            $table->text('ai_feedback')->nullable();
            $table->enum('ai_grading_status', ['pending', 'success', 'failed'])->nullable();
            $table->timestamps();

            $table->unique(['exam_attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};