<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $user = $request->user();

        // Only allow direct enrollment for free courses
        if ($course->price > 0) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Kursus berbayar harus melalui keranjang dan checkout.');
        }

        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        $user->enrollments()->create([
            'course_id' => $course->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Successfully enrolled!');
    }
}