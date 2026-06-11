<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\TutorPayment;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        $courses = $tutor->courses()->withCount('enrollments')->latest()->get();

        return view('tutor.courses.index', compact('courses', 'tutor'));
    }

    public function modules(Course $course)
    {
        $this->authorizeTutor($course);
        $course->load('modules.quiz.questions');
        return view('tutor.courses.modules', compact('course'));
    }

    public function payments(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        $payments = TutorPayment::where('tutor_id', $tutor->id)
            ->latest()
            ->paginate(20);

        $totalReceived = TutorPayment::where('tutor_id', $tutor->id)
            ->where('status', 'paid')
            ->sum('amount');

        return view('tutor.payments', compact('payments', 'totalReceived', 'tutor'));
    }

    private function authorizeTutor(Course $course): void
    {
        if ($course->tutor_id !== auth()->user()->tutor?->id) {
            abort(403, 'Unauthorized.');
        }
    }
}