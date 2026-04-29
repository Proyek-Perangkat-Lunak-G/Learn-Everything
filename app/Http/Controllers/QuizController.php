<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        $quiz->load('questions', 'module.course');

        return view('courses.quiz', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $quiz->load('questions');
        $answers = $request->input('answers', []);

        $correct = 0;
        $total = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            if (isset($answers[$question->id]) && $answers[$question->id] === $question->correct_answer) {
                $correct++;
            }
        }

        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        $passed = $score >= $quiz->passing_score;

        QuizAttempt::create([
            'user_id' => $request->user()->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
            'answers' => $answers,
        ]);

        if ($passed) {
            return redirect()->route('courses.show', $quiz->module->course)
                ->with('success', "Quiz passed with score {$score}%!");
        }

        return back()->with('error', "Quiz failed with score {$score}%. Required: {$quiz->passing_score}%. Try again!");
    }
}