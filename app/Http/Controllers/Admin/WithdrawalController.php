<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TutorWithdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = TutorWithdrawal::with(['tutor.user', 'user', 'approvedBy'])
            ->latest()
            ->paginate(20);

        $pendingCount = TutorWithdrawal::where('status', 'pending')->count();
        $totalPending = TutorWithdrawal::where('status', 'pending')->sum('amount');

        return view('admin.withdrawals.index', compact('withdrawals', 'pendingCount', 'totalPending'));
    }

    public function approve(Request $request, TutorWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal sudah diproses.');
        }

        $withdrawal->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Withdrawal disetujui. Silakan lakukan transfer dan tandai sebagai selesai.');
    }

    public function complete(Request $request, TutorWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'approved') {
            return back()->with('error', 'Withdrawal harus diapprove terlebih dahulu.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $withdrawal->update([
            'status' => 'completed',
            'completed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Withdrawal berhasil diselesaikan.');
    }

    public function reject(Request $request, TutorWithdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal sudah diproses.');
        }

        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        // Return to tutor's or seller's available balance
        if ($withdrawal->tutor_id) {
            $withdrawal->tutor->increment('wallet_available', $withdrawal->amount);
        } elseif ($withdrawal->user_id) {
            $withdrawal->user->increment('wallet_available', $withdrawal->amount);
        }

        $withdrawal->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Withdrawal ditolak. Saldo dikembalikan.');
    }
}
