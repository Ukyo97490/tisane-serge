<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('active', true)->orderBy('sort_order')->get();

        $query = Product::where('active', true)->with('category');

        if ($request->filled('categorie')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->categorie));
        }

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $products = $query->orderBy('sort_order')->paginate(12)->withQueryString();

        $currentCategory = $request->filled('categorie')
            ? $categories->firstWhere('slug', $request->categorie)
            : null;

        return view('shop.index', compact('products', 'categories', 'currentCategory'));
    }

    public function show(string $slug)
    {
        $product  = Product::where('slug', $slug)->where('active', true)->with('category')->firstOrFail();
        $related  = Product::where('category_id', $product->category_id)
            ->where('active', true)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}
