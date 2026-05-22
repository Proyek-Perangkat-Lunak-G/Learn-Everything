<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class MyProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('seller_id', $request->user()->id);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        
        // Stats
        $totalProducts = Product::where('seller_id', $request->user()->id)->count();
        $activeProducts = Product::where('seller_id', $request->user()->id)->where('is_active', true)->count();
        $inactiveProducts = Product::where('seller_id', $request->user()->id)->where('is_active', false)->count();
        $digitalProducts = Product::where('seller_id', $request->user()->id)->where('type', 'digital')->count();
        $physicalProducts = Product::where('seller_id', $request->user()->id)->where('type', 'physical')->count();

        return view('my-products.index', compact(
            'products', 
            'totalProducts', 
            'activeProducts', 
            'inactiveProducts',
            'digitalProducts',
            'physicalProducts'
        ));
    }
}