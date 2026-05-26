<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class QuizApiController extends Controller
{
    public function show(Request $request, Quiz $quiz)
    {
        $user = $request->user();
        $module = $quiz->module;
        $course = $module->course;

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Belum terdaftar di kursus ini'], 403);
        }

        $quiz->load('questions');

        $questions = $quiz->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question' => $q->question,
                'options' => $q->options,
            ];
        });

        return response()->json([
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'passing_score' => $quiz->passing_score,
                'module_id' => $quiz->module_id,
            ],
            'questions' => $questions,
        ]);
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $user = $request->user();
        $module = $quiz->module;
        $course = $module->course;

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Belum terdaftar di kursus ini'], 403);
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        $questions = $quiz->questions;
        $correct = 0;
        $total = $questions->count();

        foreach ($questions as $question) {
            $userAnswer = $request->answers[$question->id] ?? null;
            if ($userAnswer === $question->correct_answer) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        $passed = $score >= $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
            'answers' => $request->answers,
        ]);

        return response()->json([
            'score' => $score,
            'passed' => $passed,
            'correct' => $correct,
            'total' => $total,
            'attempt_id' => $attempt->id,
        ]);
    }
}