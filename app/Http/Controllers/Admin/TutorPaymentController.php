<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use App\Models\TutorPayment;
use App\Models\HomeVisitBooking;
use Illuminate\Http\Request;

class TutorPaymentController extends Controller
{
    public function index()
    {
        // Get tutors with pending invoices (wallet_pending > 0)
        $pendingInvoices = Tutor::with('user')
            ->where('status', 'approved')
            ->where('wallet_pending', '>', 0)
            ->orderBy('wallet_pending', 'desc')
            ->get();

        // Payment history
        $payments = TutorPayment::with(['tutor.user', 'admin'])
            ->latest()
            ->paginate(20);

        $totalPaid = TutorPayment::where('status', 'paid')->sum('amount');
        $totalPending = Tutor::where('status', 'approved')->sum('wallet_pending');

        return view('admin.tutor-payments.index', compact('pendingInvoices', 'payments', 'totalPaid', 'totalPending'));
    }

    public function payTutor(Request $request, Tutor $tutor)
    {
        if ($tutor->wallet_pending <= 0) {
            return back()->with('error', 'Tutor tidak memiliki tagihan pending.');
        }

        // Transfer from pending to available
        $amount = $tutor->wallet_pending;
        
        $tutor->update([
            'wallet_pending' => 0,
            'wallet_available' => $tutor->wallet_available + $amount,
        ]);

        // Create payment record
        TutorPayment::create([
            'tutor_id' => $tutor->id,
            'admin_id' => $request->user()->id,
            'amount' => $amount,
            'period' => now()->format('F Y'),
            'notes' => 'Pembayaran otomatis dari tagihan booking',
            'status' => 'paid',
        ]);

        return back()->with('success', "Pembayaran Rp " . number_format($amount, 0, ',', '.') . " ke {$tutor->user->name} berhasil.");
    }
}
