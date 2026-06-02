<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Course;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user());
        $cart->load('items.itemable');

        return view('cart.index', compact('cart'));
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $cart = $this->getOrCreateCart($request->user());
        $product = Product::findOrFail($request->product_id);

        // Prevent users from adding their own products to cart
        if ($product->seller_id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menambahkan produk sendiri ke keranjang.');
        }

        $existingItem = $cart->items()
            ->where('itemable_type', Product::class)
            ->where('itemable_id', $product->id)
            ->first();

        if ($existingItem) {
            $existingItem->update(['quantity' => $existingItem->quantity + ($request->quantity ?? 1)]);
        } else {
            $cart->items()->create([
                'itemable_type' => Product::class,
                'itemable_id' => $product->id,
                'quantity' => $request->quantity ?? 1,
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Added to cart!');
    }

    public function addCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $cart = $this->getOrCreateCart($request->user());
        $course = Course::findOrFail($request->course_id);

        $exists = $cart->items()
            ->where('itemable_type', Course::class)
            ->where('itemable_id', $course->id)
            ->exists();

        if (!$exists) {
            $cart->items()->create([
                'itemable_type' => Course::class,
                'itemable_id' => $course->id,
                'quantity' => 1,
                'price' => $course->price,
            ]);
        }

        return back()->with('success', 'Course added to cart!');
    }

    public function remove(CartItem $item)
    {
        if ($item->cart->user_id !== request()->user()->id) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    private function getOrCreateCart($user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }
}
