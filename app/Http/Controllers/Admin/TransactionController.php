<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Enrollment;
use App\Models\Subscription;
use App\Models\Product;
use App\Models\HomeVisitBooking;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('transaction_code', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $transactions = $query->latest()->paginate(20);
        $totalRevenue = Transaction::where('status', 'success')->sum('total_amount');

        return view('admin.transactions.index', compact('transactions', 'totalRevenue'));
    }

    public function approve(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        $transaction->update(['status' => 'success']);

        // Process based on transaction type
        if ($transaction->type === 'purchase') {
            // Enroll user in courses from items
            $items = $transaction->items ?? [];
            foreach ($items as $item) {
                if (($item['type'] ?? '') === 'Course') {
                    Enrollment::firstOrCreate([
                        'user_id' => $transaction->user_id,
                        'course_id' => $item['id'],
                    ]);
                }
                
                // Add earnings to seller's wallet for products
                if (($item['type'] ?? '') === 'Product') {
                    $product = Product::find($item['id']);
                    if ($product && $product->seller_id) {
                        $seller = User::find($product->seller_id);
                        if ($seller) {
                            $itemTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                            $seller->increment('wallet_pending', $itemTotal);

                            // Notify buyer via chat to expect digital file from seller
                            Chat::create([
                                'sender_id'   => $seller->id,
                                'receiver_id' => $transaction->user_id,
                                'message'     => "Halo! Pembayaran pesanan Anda untuk \"{$product->name}\" telah dikonfirmasi. Saya akan segera mengirimkan file produk digital ke sini.",
                            ]);
                        }
                    }
                }
            }
        } elseif ($transaction->type === 'subscription') {
            // Activate subscription from notes
            $package = 'basic';
            if (str_contains($transaction->notes ?? '', 'premium')) {
                $package = 'premium';
            }
            $prices = ['basic' => 99000, 'premium' => 199000];

            Subscription::create([
                'user_id' => $transaction->user_id,
                'package' => $package,
                'price' => $prices[$package],
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);
        } elseif ($transaction->type === 'booking') {
            // Confirm the home visit booking and credit tutor wallet
            $booking = HomeVisitBooking::where('transaction_id', $transaction->id)->first();
            if ($booking) {
                $booking->update([
                    'is_paid' => true,
                    'paid_at' => now(),
                    'status' => 'confirmed',
                ]);
                $booking->tutor->increment('wallet_pending', $booking->price);
            }
        }

        return back()->with('success', 'Transaksi berhasil dikonfirmasi.');
    }

    public function reject(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi ini sudah diproses.');
        }

        $transaction->update(['status' => 'failed']);

        return back()->with('success', 'Transaksi ditolak.');
    }
}
