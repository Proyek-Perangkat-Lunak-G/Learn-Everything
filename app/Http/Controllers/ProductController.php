<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('seller')->where('is_active', true);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('marketplace.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('seller');
        return view('marketplace.show', compact('product'));
    }

    public function create()
    {
        return view('marketplace.create');
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['seller_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('marketplace.index')->with('success', 'Product listed!');
    }

    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        return view('marketplace.edit', compact('product'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $this->authorizeProduct($product);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('marketplace.index')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('marketplace.index')->with('success', 'Product deleted.');
    }

    public function toggleStatus(Product $product)
    {
        $this->authorizeProduct($product);

        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Produk berhasil {$status}.");
    }

    private function authorizeProduct(Product $product): void
    {
        if ($product->seller_id !== request()->user()->id && !request()->user()->isAdmin()) {
            abort(403);
        }
    }
}