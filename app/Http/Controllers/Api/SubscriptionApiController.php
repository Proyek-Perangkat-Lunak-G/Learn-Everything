<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionApiController extends Controller
{
    public function packages()
    {
        $packages = [
            'basic' => [
                'name' => 'Basic',
                'price' => 99000,
                'features' => ['Akses semua kursus', 'Forum diskusi', 'Sertifikat digital'],
            ],
            'premium' => [
                'name' => 'Premium',
                'price' => 199000,
                'features' => ['Semua fitur Basic', 'Home visit tutor', 'Chat prioritas', 'Diskon marketplace 10%'],
            ],
        ];

        return response()->json($packages);
    }

    public function current(Request $request)
    {
        $subscription = $request->user()->subscription;

        return response()->json([
            'subscription' => $subscription,
            'is_active' => $subscription ? $subscription->isActive() : false,
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,premium',
            'payment_method' => 'required|in:transfer,ewallet',
        ]);

        $prices = ['basic' => 99000, 'premium' => 199000];

        $subscription = Subscription::create([
            'user_id' => $request->user()->id,
            'package' => $request->package,
            'price' => $prices[$request->package],
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
        ]);

        return response()->json([
            'message' => 'Berlangganan berhasil',
            'subscription' => $subscription,
        ], 201);
    }
}
