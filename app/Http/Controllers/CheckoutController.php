<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->with('items.itemable')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Your cart is empty.');
        }

        // Check if cart has physical products
        $hasPhysicalProducts = $cart->items->contains(function($item) {
            return $item->itemable_type === 'App\\Models\\Product' && $item->itemable->type === 'physical';
        });

        $addresses = $request->user()->addresses;

        return view('checkout.index', compact('cart', 'hasPhysicalProducts', 'addresses'));
    }

    public function process(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items.itemable')->firstOrFail();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('info', 'Your cart is empty.');
        }

        // Check if cart has physical products
        $hasPhysicalProducts = $cart->items->contains(function($item) {
            return $item->itemable_type === 'App\\Models\\Product' && $item->itemable->type === 'physical';
        });

        // Validate based on product types
        $rules = ['payment_method' => 'required|in:transfer,ewallet'];
        
        if ($hasPhysicalProducts) {
            $rules['address_id'] = 'required|exists:addresses,id';
        }

        $request->validate($rules);

        $items = $cart->items->map(fn($item) => [
            'type' => class_basename($item->itemable_type),
            'id' => $item->itemable_id,
            'name' => $item->itemable->name ?? $item->itemable->title ?? 'Item',
            'quantity' => $item->quantity,
            'price' => $item->price,
        ])->toArray();

        // Prepare transaction data
        $transactionData = [
            'user_id' => $user->id,
            'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
            'total_amount' => $cart->total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'items' => $items,
            'type' => 'purchase',
        ];

        // Add shipping info if physical products
        if ($hasPhysicalProducts && $request->address_id) {
            $address = \App\Models\Address::find($request->address_id);
            $transactionData['address_id'] = $address->id;
            $transactionData['shipping_address'] = $address->address . ', ' . $address->city . ', ' . $address->province . ' ' . $address->postal_code;
            $transactionData['shipping_phone'] = $user->phone;
        }

        $transaction = Transaction::create($transactionData);

        // Clear cart
        $cart->items()->delete();

        return redirect()->route('transactions.upload-proof', $transaction)
            ->with('info', 'Silakan upload bukti pembayaran untuk menyelesaikan transaksi.');
    }

    public function uploadProofForm(Transaction $transaction)
    {
        $user = request()->user();
        if ($transaction->user_id !== $user->id) {
            abort(403);
        }
        if ($transaction->status !== 'pending') {
            return redirect()->route('dashboard')->with('info', 'Transaksi ini sudah diproses.');
        }

        return view('transactions.upload-proof', compact('transaction'));
    }

    public function uploadProof(Request $request, Transaction $transaction)
    {
        $user = $request->user();
        if ($transaction->user_id !== $user->id) {
            abort(403);
        }
        if ($transaction->status !== 'pending') {
            return redirect()->route('dashboard')->with('info', 'Transaksi ini sudah diproses.');
        }

        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $transaction->update(['payment_proof' => $path]);

        return redirect()->route('dashboard')
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
    }
}
