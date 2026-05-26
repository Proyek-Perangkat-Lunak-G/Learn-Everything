<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TutorWithdrawal;
use Illuminate\Http\Request;

class SellerWalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get user's products
        $products = $user->products;
        
        // Calculate earnings from successful transactions
        $salesDetails = [];
        $totalEarnings = 0;
        
        if ($products->isNotEmpty()) {
            $productIds = $products->pluck('id')->toArray();
            
            // Get all successful transactions containing user's products
            $transactions = Transaction::where('status', 'success')
                ->whereNotNull('items')
                ->get();
            
            foreach ($transactions as $transaction) {
                foreach ($transaction->items as $item) {
                    if (($item['type'] ?? '') === 'Product' && in_array($item['id'] ?? 0, $productIds)) {
                        $amount = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                        $totalEarnings += $amount;
                        $salesDetails[] = [
                            'transaction_code' => $transaction->transaction_code,
                            'product_name' => $item['name'] ?? '-',
                            'quantity' => $item['quantity'] ?? 1,
                            'price' => $item['price'] ?? 0,
                            'total' => $amount,
                            'date' => $transaction->created_at,
                        ];
                    }
                }
            }
        }
        
        // Get withdrawal requests
        $withdrawals = TutorWithdrawal::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
        return view('seller.wallet', compact('user', 'products', 'salesDetails', 'totalEarnings', 'withdrawals'));
    }

    public function requestWithdrawal(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'amount' => 'required|numeric|min:10000|max:' . $user->wallet_available,
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_holder' => 'required|string|max:100',
        ]);
        
        if ($user->wallet_available < $request->amount) {
            return back()->with('error', 'Saldo tidak mencukupi.');
        }
        
        // Deduct from available wallet
        $user->decrement('wallet_available', $request->amount);
        
        // Create withdrawal request (reuse TutorWithdrawal table)
        TutorWithdrawal::create([
            'user_id' => $user->id,
            'tutor_id' => null, // For sellers, tutor_id is null
            'amount' => $request->amount,
            'status' => 'pending',
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
        ]);
        
        return back()->with('success', 'Permintaan penarikan berhasil diajukan. Menunggu persetujuan admin.');
    }
}
