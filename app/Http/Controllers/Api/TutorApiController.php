<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\TutorReview;
use App\Models\HomeVisitBooking;
use Illuminate\Http\Request;

class TutorApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Tutor::with('user')->where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhere('bio', 'like', "%{$search}%");
            });
        }

        $tutors = $query->orderByDesc('rating')->paginate(12);

        return response()->json($tutors);
    }

    public function show(Tutor $tutor)
    {
        $tutor->load(['user', 'courses' => function ($q) {
            $q->where('is_published', true);
        }, 'reviews.user']);

        return response()->json($tutor);
    }

    public function storeReview(Request $request, Tutor $tutor)
    {
        $user = $request->user();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = TutorReview::updateOrCreate(
            ['user_id' => $user->id, 'tutor_id' => $tutor->id],
            ['rating' => $validated['rating'], 'comment' => $validated['comment'] ?? null]
        );

        $tutor->updateRating();

        return response()->json($review, 201);
    }

    public function book(Request $request, Tutor $tutor)
    {
        $user = $request->user();

        $validated = $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|string',
            'location' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking = HomeVisitBooking::create([
            'user_id' => $user->id,
            'tutor_id' => $tutor->id,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'location' => $validated['location'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json($booking, 201);
    }

    public function myBookings(Request $request)
    {
        $user = $request->user();

        $bookings = HomeVisitBooking::with('tutor.user')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json($bookings);
    }

    public function updateBookingStatus(Request $request, HomeVisitBooking $booking)
    {
        $user = $request->user();

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $status = $request->status;

        // Tutor can confirm/complete
        if (in_array($status, ['confirmed', 'completed'])) {
            if (!$user->isTutor() || $user->tutor->id !== $booking->tutor_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        // User can cancel
        if ($status === 'cancelled') {
            if ($booking->user_id !== $user->id && (!$user->isTutor() || $user->tutor->id !== $booking->tutor_id)) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        $booking->update(['status' => $status]);

        return response()->json($booking);
    }
}
