<?php

namespace App\Http\Controllers;

use App\Models\ModuleProgress;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all enrollments with course details and modules
        $enrollments = $user->enrollments()
            ->with(['course.modules'])
            ->latest()
            ->paginate(12);

        // Efficiently load all module progresses for this user across all enrolled courses
        $allModuleIds = $enrollments->flatMap(fn($e) => $e->course->modules->pluck('id'))->unique()->values()->all();
        $allProgresses = !empty($allModuleIds)
            ? ModuleProgress::where('user_id', $user->id)->whereIn('module_id', $allModuleIds)->get()
            : collect();

        // Attach matching progresses to each enrollment as a virtual relation
        foreach ($enrollments as $enrollment) {
            $courseModuleIds = $enrollment->course->modules->pluck('id');
            $enrollment->setRelation('moduleProgresses',
                $allProgresses->filter(fn($p) => $courseModuleIds->contains($p->module_id))->values()
            );
        }

        // Calculate stats
        $totalCourses = $user->enrollments()->count();
        $completedCourses = $user->enrollments()->where('is_completed', true)->count();
        $inProgressCourses = $user->enrollments()->where('is_completed', false)->count();

        return view('my-courses.index', compact('enrollments', 'totalCourses', 'completedCourses', 'inProgressCourses'));
    }
}
