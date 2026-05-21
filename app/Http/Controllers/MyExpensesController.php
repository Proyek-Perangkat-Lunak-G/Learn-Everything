<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class MyExpensesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get all successful transactions (user's expenses)
        $transactions = $user->transactions()
            ->where('status', 'success')
            ->with('address')
            ->latest()
            ->paginate(20);
        
        // Calculate totals by type
        $totalSpent = $user->transactions()->where('status', 'success')->sum('total_amount');
        $courseSpent = $user->transactions()
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->get()
            ->sum(function($tx) {
                return collect($tx->items)->where('type', 'Course')->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
            });
        $productSpent = $user->transactions()
            ->where('status', 'success')
            ->where('type', 'purchase')
            ->get()
            ->sum(function($tx) {
                return collect($tx->items)->where('type', 'Product')->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
            });
        $bookingSpent = $user->transactions()
            ->where('status', 'success')
            ->where('type', 'booking')
            ->sum('total_amount');
        $subscriptionSpent = $user->transactions()
            ->where('status', 'success')
            ->where('type', 'subscription')
            ->sum('total_amount');
        
        return view('my-expenses.index', compact('transactions', 'totalSpent', 'courseSpent', 'productSpent', 'bookingSpent', 'subscriptionSpent'));
    }
}
