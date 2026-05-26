<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\TutorReview;
use Illuminate\Http\Request;

class TutorReviewController extends Controller
{
    public function store(Request $request, Tutor $tutor)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        TutorReview::updateOrCreate(
            ['user_id' => $request->user()->id, 'tutor_id' => $tutor->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        $tutor->updateRating();

        return back()->with('success', 'Review submitted!');
    }
}