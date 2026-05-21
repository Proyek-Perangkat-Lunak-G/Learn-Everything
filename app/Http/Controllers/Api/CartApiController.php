<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Course;
use App\Models\Product;
use App\Models\Enrollment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = Cart::with('items.itemable')->firstOrCreate(['user_id' => $user->id]);

        return response()->json([
            'items' => $cart->items,
            'total' => $cart->total,
        ]);
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $user = $request->user();

        if ($user->role !== 'user') {
            return response()->json(['message' => 'Hanya user yang bisa belanja'], 403);
        }

        $product = Product::findOrFail($request->product_id);
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $existing = CartItem::where('cart_id', $cart->id)
            ->where('itemable_type', Product::class)
            ->where('itemable_id', $product->id)
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + ($request->quantity ?? 1)]);
            return response()->json(['message' => 'Jumlah produk diperbarui', 'item' => $existing]);
        }

        $item = CartItem::create([
            'cart_id' => $cart->id,
            'itemable_type' => Product::class,
            'itemable_id' => $product->id,
            'quantity' => $request->quantity ?? 1,
            'price' => $product->price,
        ]);

        return response()->json(['message' => 'Produk ditambahkan ke keranjang', 'item' => $item], 201);
    }

    public function addCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = $request->user();
        $course = Course::findOrFail($request->course_id);

        $enrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($enrolled) {
            return response()->json(['message' => 'Sudah terdaftar di kursus ini'], 409);
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $existing = CartItem::where('cart_id', $cart->id)
            ->where('itemable_type', Course::class)
            ->where('itemable_id', $course->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Kursus sudah ada di keranjang'], 409);
        }

        $item = CartItem::create([
            'cart_id' => $cart->id,
            'itemable_type' => Course::class,
            'itemable_id' => $course->id,
            'quantity' => 1,
            'price' => $course->price,
        ]);

        return response()->json(['message' => 'Kursus ditambahkan ke keranjang', 'item' => $item], 201);
    }

    public function removeItem(Request $request, CartItem $cartItem)
    {
        $user = $request->user();

        if ($cartItem->cart->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang']);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        $cart = Cart::with('items.itemable')->where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong'], 400);
        }

        $request->validate([
            'payment_method' => 'required|in:transfer,ewallet,cod',
        ]);

        $items = $cart->items->map(function ($item) {
            return [
                'type' => class_basename($item->itemable_type),
                'name' => $item->itemable->name ?? $item->itemable->title ?? '-',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->price * $item->quantity,
            ];
        })->toArray();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
            'total_amount' => $cart->total,
            'status' => 'completed',
            'payment_method' => $request->payment_method,
            'items' => $items,
        ]);

        // Enroll user in purchased courses
        foreach ($cart->items as $item) {
            if ($item->itemable_type === Course::class) {
                Enrollment::firstOrCreate(
                    ['user_id' => $user->id, 'course_id' => $item->itemable_id],
                    ['progress' => 0]
                );
            }
        }

        $cart->items()->delete();

        return response()->json([
            'message' => 'Checkout berhasil',
            'transaction' => $transaction,
        ], 201);
    }
}
