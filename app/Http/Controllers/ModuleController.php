<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleProgress;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function learn(Request $request, Course $course, Module $module)
    {
        $user = $request->user();

        // Check enrollment
        $enrollment = $user->enrollments()->where('course_id', $course->id)->firstOrFail();

        $course->load('modules');

        // Get or create progress
        $progress = ModuleProgress::firstOrCreate(
            ['user_id' => $user->id, 'module_id' => $module->id]
        );

        return view('courses.learn', compact('course', 'module', 'enrollment', 'progress'));
    }

    public function complete(Request $request, Course $course, Module $module)
    {
        $user = $request->user();
        $enrollment = $user->enrollments()->where('course_id', $course->id)->firstOrFail();

        ModuleProgress::updateOrCreate(
            ['user_id' => $user->id, 'module_id' => $module->id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        // Update enrollment progress
        $totalModules = $course->modules()->count();
        $completedModules = ModuleProgress::where('user_id', $user->id)
            ->whereIn('module_id', $course->modules()->pluck('id'))
            ->where('is_completed', true)
            ->count();

        $percentage = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;
        $enrollment->update([
            'progress' => $percentage,
            'is_completed' => $percentage >= 100,
            'completed_at' => $percentage >= 100 ? now() : null,
        ]);

        // Go to next module or back to course
        $nextModule = $course->modules()->where('order', '>', $module->order)->first();
        if ($nextModule) {
            return redirect()->route('courses.learn', [$course, $nextModule])->with('success', 'Module completed!');
        }

        return redirect()->route('courses.show', $course)->with('success', 'All modules completed! 🎉');
    }
}