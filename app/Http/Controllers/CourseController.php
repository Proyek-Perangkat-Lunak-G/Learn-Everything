<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
//ini scrum 77 dan 79 prana
class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['modules'])->where('is_published', true);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $courses = $query->latest()->paginate(12);
        $categories = Course::where('is_published', true)->distinct()->pluck('category');

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        $course->load(['modules.quiz', 'enrollments']);

        return view('courses.show', compact('course'));
    }
}