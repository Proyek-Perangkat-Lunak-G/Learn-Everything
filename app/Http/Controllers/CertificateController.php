<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function history(Request $request)
    {
        $enrollments = $request->user()->enrollments()
            ->with(['course', 'certificate'])
            ->latest()
            ->get();

        return view('profile.history', compact('enrollments'));
    }

    public function download(Request $request, Enrollment $enrollment)
    {
        if ($enrollment->user_id !== $request->user()->id) {
            abort(403);
        }

        if (!$enrollment->is_completed) {
            return back()->with('error', 'Course not completed yet.');
        }

        $certificate = Certificate::firstOrCreate(
            ['enrollment_id' => $enrollment->id],
            [
                'user_id' => $request->user()->id,
                'certificate_number' => 'CERT-' . strtoupper(Str::random(10)),
                'issued_at' => now(),
            ]
        );

        $enrollment->load('course.tutor.user');

        return view('certificates.pdf', compact('certificate', 'enrollment'));
    }
}