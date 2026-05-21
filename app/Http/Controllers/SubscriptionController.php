<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $currentSubscription = $request->user()->subscription;

        $packages = [
            'basic' => [
                'name' => 'Basic',
                'price' => 99000,
                'features' => ['Access to all courses', 'Forum access', 'Email support'],
                'duration' => 30,
            ],
            'premium' => [
                'name' => 'Premium',
                'price' => 199000,
                'features' => ['Everything in Basic', 'Live chat with tutors', 'Home visit booking', 'Certificate generation', 'Priority support'],
                'duration' => 30,
            ],
        ];

        return view('subscriptions.index', compact('packages', 'currentSubscription'));
    }

    public function payment(Request $request, string $package)
    {
        if (!in_array($package, ['basic', 'premium'])) {
            abort(404);
        }

        $prices = ['basic' => 99000, 'premium' => 199000];
        $price = $prices[$package];

        return view('subscriptions.payment', compact('package', 'price'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,premium',
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card',
        ]);

        $prices = ['basic' => 99000, 'premium' => 199000];
        $package = $request->package;
        $price = $prices[$package];

        // Create transaction as pending
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'transaction_code' => 'TRX-SUB-' . strtoupper(Str::random(8)),
            'total_amount' => $price,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'type' => 'subscription',
            'notes' => "Subscription {$package} - 30 hari",
        ]);

        return redirect()->route('transactions.upload-proof', $transaction)
            ->with('info', 'Silakan upload bukti pembayaran untuk mengaktifkan langganan.');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,premium',
        ]);

        return redirect()->route('subscriptions.payment', $request->package);
    }
}
