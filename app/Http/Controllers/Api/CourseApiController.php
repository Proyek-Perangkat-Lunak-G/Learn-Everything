<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('modules')->where('is_published', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $courses = $query->latest()->paginate(12);

        return response()->json($courses);
    }

    public function show(Course $course)
    {
        $course->load(['modules.quiz']);
        $enrolled = false;
        $progress = null;

        if (auth('sanctum')->check()) {
            $enrollment = Enrollment::where('user_id', auth('sanctum')->id())
                ->where('course_id', $course->id)
                ->first();
            $enrolled = (bool) $enrollment;
            $progress = $enrollment?->progress;
        }

        return response()->json([
            'course' => $course,
            'enrolled' => $enrolled,
            'progress' => $progress,
        ]);
    }

    public function enroll(Request $request, Course $course)
    {
        $user = $request->user();

        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Sudah terdaftar di kursus ini'], 409);
        }

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'progress' => 0,
        ]);

        return response()->json($enrollment, 201);
    }

    public function modules(Course $course)
    {
        $course->load('modules.quiz');
        return response()->json($course->modules);
    }

    public function learnModule(Request $request, Module $module)
    {
        $user = $request->user();
        $course = $module->course;

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Belum terdaftar di kursus ini'], 403);
        }

        $moduleProgress = ModuleProgress::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        return response()->json([
            'module' => $module->load('quiz.questions'),
            'completed' => (bool) $moduleProgress?->is_completed,
        ]);
    }

    public function completeModule(Request $request, Module $module)
    {
        $user = $request->user();
        $course = $module->course;

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'Belum terdaftar di kursus ini'], 403);
        }

        ModuleProgress::updateOrCreate(
            ['user_id' => $user->id, 'module_id' => $module->id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        $totalModules = $course->modules()->count();
        $completedModules = ModuleProgress::where('user_id', $user->id)
            ->whereIn('module_id', $course->modules()->pluck('id'))
            ->where('is_completed', true)
            ->count();

        $progress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;

        $enrollment->update([
            'progress' => $progress,
            'is_completed' => $progress >= 100,
            'completed_at' => $progress >= 100 ? now() : null,
        ]);

        return response()->json([
            'progress' => $progress,
            'is_completed' => $progress >= 100,
        ]);
    }

    public function myEnrollments(Request $request)
    {
        $enrollments = Enrollment::with('course')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(12);

        return response()->json($enrollments);
    }
}