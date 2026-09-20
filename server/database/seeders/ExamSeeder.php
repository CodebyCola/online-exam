<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        // ── Akun tetap untuk login manual saat development ──
        // Password semua akun ini: "password" (lihat UserFactory)

        $dosen = User::factory()->dosen()->create([
            'name' => 'Budi Santoso',
            'email' => 'dosen@example.com',
        ]);

        $mahasiswa = User::factory()->mahasiswa()->create([
            'name' => 'Siti Aminah',
            'email' => 'mahasiswa@example.com',
        ]);

        // Mahasiswa tambahan untuk simulasi banyak peserta
        $otherStudents = User::factory()->mahasiswa()->count(4)->create();

        // ── Ujian 1: published, siap dikerjakan ──
        $exam = Exam::factory()->published()->create([
            'lecturer_id' => $dosen->id,
            'title' => 'UTS Pemrograman Web',
            'opens_at' => now()->subMinutes(10),
            'closes_at' => now()->addHours(2),
            'duration_minutes' => 90,
        ]);

        $this->seedQuestionsFor($exam);

        // Satu attempt contoh milik mahasiswa utama, masih berjalan
        ExamAttempt::factory()->create([
            'exam_id' => $exam->id,
            'student_id' => $mahasiswa->id,
            'started_at' => now()->subMinutes(5),
        ]);

        // ── Ujian 2: sudah selesai & ter-grade, untuk lihat hasil ──
        $finishedExam = Exam::factory()->closed()->create([
            'lecturer_id' => $dosen->id,
            'title' => 'Kuis Struktur Data',
            'opens_at' => now()->subDays(2),
            'closes_at' => now()->subDays(2)->addHours(1),
            'duration_minutes' => 45,
        ]);

        $questions = $this->seedQuestionsFor($finishedExam);

        foreach ($otherStudents as $student) {
            $attempt = ExamAttempt::factory()->graded()->create([
                'exam_id' => $finishedExam->id,
                'student_id' => $student->id,
            ]);

            $this->seedAnswersFor($attempt, $questions);
        }

        // ── Ujian 3: draft, untuk test alur dosen bikin soal ──
        Exam::factory()->create([
            'lecturer_id' => $dosen->id,
            'title' => 'Draft Ujian Baru',
        ]);
    }

    /**
     * Bikin 2 soal pilihan ganda + 1 soal esai untuk satu ujian.
     * Dipakai berulang, jadi ditarik ke method sendiri.
     */
    private function seedQuestionsFor(Exam $exam): array
    {
        $mcQuestions = Question::factory()
            ->multipleChoice()
            ->count(2)
            ->sequence(fn ($sequence) => ['order' => $sequence->index + 1])
            ->create(['exam_id' => $exam->id]);

        foreach ($mcQuestions as $question) {
            Option::factory()->correct()->create(['question_id' => $question->id]);
            Option::factory()->count(3)->create(['question_id' => $question->id]);
        }

        $essayQuestion = Question::factory()
            ->essay()
            ->create(['exam_id' => $exam->id, 'order' => 3, 'points' => 20]);

        return [...$mcQuestions->all(), $essayQuestion];
    }

    /**
     * Bikin jawaban dummy (pilihan ganda + esai ter-grade)
     * untuk satu attempt yang sudah selesai.
     */
    private function seedAnswersFor(ExamAttempt $attempt, array $questions): void
    {
        foreach ($questions as $question) {
            if ($question->isEssay()) {
                Answer::factory()->essay()->essayGraded()->create([
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ]);

                continue;
            }

            $correctOption = $question->options()->where('is_correct', true)->first();

            Answer::factory()->create([
                'exam_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'selected_option_id' => $correctOption->id,
                'score' => $question->points,
            ]);
        }
    }
}