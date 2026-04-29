<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\HomeVisitBooking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\TutorAvailability;

class BookingController extends Controller
{
    public function create(Tutor $tutor)
    {
        // Get tutor availabilities that are available, not booked, and in the future
        $availabilities = $tutor->availabilities()
            ->where('is_available', true)
            ->where('is_booked', false)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('tutors.booking', compact('tutor', 'availabilities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tutor_id' => 'required|exists:tutors,id',
            'availability_id' => 'required|exists:tutor_availabilities,id',
            'location' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tutor = Tutor::findOrFail($request->tutor_id);
        $availability = TutorAvailability::where('id', $request->availability_id)
            ->where('tutor_id', $tutor->id)
            ->where('is_available', true)
            ->where('is_booked', false)
            ->firstOrFail();

        // Calculate duration in hours
        $startTime = Carbon::parse($availability->start_time);
        $endTime = Carbon::parse($availability->end_time);
        $durationHours = max(1, $endTime->diffInHours($startTime));

        // Calculate price
        $price = $tutor->hourly_rate * $durationHours;

        // Create transaction first
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'transaction_code' => 'TRX-' . strtoupper(\Str::random(10)),
            'type' => 'booking',
            'total_amount' => $price,
            'status' => 'pending',
            'items' => [
                [
                    'type' => 'HomeVisitBooking',
                    'name' => 'Booking Tutor ' . $tutor->user->name,
                    'price' => $price,
                    'quantity' => 1,
                ]
            ],
        ]);

        // Create booking
        $booking = HomeVisitBooking::create([
            'user_id' => $request->user()->id,
            'tutor_id' => $tutor->id,
            'date' => $availability->date,
            'time' => $availability->start_time,
            'end_time' => $availability->end_time,
            'duration_hours' => $durationHours,
            'price' => $price,
            'transaction_id' => $transaction->id,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'pending',
            'is_paid' => false,
        ]);

        // Mark availability as booked
        $availability->update(['is_booked' => true]);

        // Redirect to payment
        return redirect()->route('bookings.payment', $booking)
            ->with('info', 'Silakan lakukan pembayaran untuk menyelesaikan booking.');
    }

    public function payment(HomeVisitBooking $booking)
    {
        // Check ownership
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if already paid
        if ($booking->is_paid) {
            return redirect()->route('dashboard')->with('info', 'Booking sudah dibayar.');
        }

        $booking->load('tutor.user', 'transaction');

        return view('bookings.payment', compact('booking'));
    }

    public function processPayment(Request $request, HomeVisitBooking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->is_paid) {
            return redirect()->route('dashboard')->with('info', 'Booking sudah dibayar.');
        }

        $booking->load('transaction');

        if ($booking->transaction->payment_proof) {
            return redirect()->route('bookings.payment', $booking)
                ->with('info', 'Bukti pembayaran sudah diupload. Menunggu konfirmasi admin.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $booking->transaction->update(['payment_proof' => $path]);

        return redirect()->route('dashboard')
            ->with('success', 'Bukti pembayaran berhasil diupload. Booking Anda menunggu konfirmasi admin.');
    }

    public function updateStatus(Request $request, HomeVisitBooking $booking)
    {
        $request->validate(['status' => 'required|in:confirmed,completed,cancelled']);

        $user = $request->user();

        // Tutor can mark as completed
        if ($request->status === 'completed') {
            if (!$user->tutor || $user->tutor->id !== $booking->tutor_id) {
                abort(403);
            }
            $booking->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        // User can cancel (with refund logic if needed)
        if ($request->status === 'cancelled') {
            if ($booking->user_id !== $user->id) {
                abort(403);
            }

            // If already paid, need admin approval for refund
            if ($booking->is_paid) {
                return back()->with('error', 'Untuk pembatalan booking yang sudah dibayar, hubungi admin.');
            }

            $booking->update(['status' => 'cancelled']);
            $booking->transaction->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Status booking berhasil diupdate.');
    }
}