<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    public function index()
    {
        $tutors = Tutor::with('user')->latest()->paginate(12);

        return view('tutors.index', compact('tutors'));
    }

    public function show(Tutor $tutor)
    {
        $tutor->load(['user', 'reviews.user']);

        return view('tutors.show', compact('tutor'));
    }
}
