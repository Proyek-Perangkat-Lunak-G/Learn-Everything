<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorAvailability;
use App\Models\HomeVisitBooking;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        $availabilities = $tutor->availabilities()
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Manually attach bookings — whereColumn does not work in eager loading
        // because the parent table is not in scope during the subquery.
        $dateStrings = $availabilities->pluck('date')->map->toDateString()->unique()->values()->all();
        $bookings = !empty($dateStrings)
            ? HomeVisitBooking::with(['user', 'transaction'])
                ->where('tutor_id', $tutor->id)
                ->whereIn('date', $dateStrings)
                ->get()
                ->keyBy(fn($b) => $b->date->toDateString() . '_' . $b->time)
            : collect();

        foreach ($availabilities as $avail) {
            $key = $avail->date->toDateString() . '_' . $avail->start_time;
            $avail->setRelation('booking', $bookings->get($key));
        }

        return view('tutor.availability', compact('tutor', 'availabilities'));
    }

    public function store(Request $request)
    {
        $tutor = $request->user()->tutor;

        if (!$tutor) {
            return redirect()->route('landing')->with('error', 'Profil tutor tidak ditemukan.');
        }

        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        TutorAvailability::create([
            'tutor_id' => $tutor->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_available' => true,
        ]);

        return back()->with('success', 'Jadwal tersedia berhasil ditambahkan.');
    }

    public function destroy(TutorAvailability $availability)
    {
        if ($availability->tutor_id !== auth()->user()->tutor?->id) {
            abort(403);
        }

        $availability->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function toggle(TutorAvailability $availability)
    {
        if ($availability->tutor_id !== auth()->user()->tutor?->id) {
            abort(403);
        }

        $availability->update(['is_available' => !$availability->is_available]);

        return back()->with('success', 'Status ketersediaan berhasil diubah.');
    }

    public function completeBooking(HomeVisitBooking $booking)
    {
        $tutor = auth()->user()->tutor;
        
        if (!$tutor || $booking->tutor_id !== $tutor->id) {
            abort(403, 'Anda tidak memiliki akses untuk menyelesaikan booking ini.');
        }

        if ($booking->status === 'completed') {
            return back()->with('info', 'Booking sudah ditandai sebagai selesai.');
        }

        if (!$booking->is_paid) {
            return back()->with('error', 'Booking belum dibayar. Tidak dapat ditandai selesai.');
        }

        // Update booking status to completed
        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Add price to tutor's wallet_pending
        $tutor->increment('wallet_pending', $booking->price);

        return back()->with('success', 'Booking berhasil ditandai selesai. Saldo pending Anda bertambah Rp ' . number_format($booking->price, 0, ',', '.'));
    }
}