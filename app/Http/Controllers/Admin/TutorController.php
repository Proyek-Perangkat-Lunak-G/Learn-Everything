<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function index()
    {
        $tutors = Tutor::with('user')
            ->withCount('courses')
            ->latest()
            ->paginate(20);

        return view('admin.tutors.index', compact('tutors'));
    }

    public function verify(Request $request, Tutor $tutor)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $tutor->update(['status' => $request->status]);

        $message = $request->status === 'approved'
            ? "Tutor {$tutor->user->name} berhasil disetujui."
            : "Tutor {$tutor->user->name} ditolak.";

        return back()->with('success', $message);
    }

    public function show(Tutor $tutor)
    {
        $tutor->load('user', 'courses.enrollments', 'reviews.user', 'bookings.user');
        return view('admin.tutors.show', compact('tutor'));
    }
}
