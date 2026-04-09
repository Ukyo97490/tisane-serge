<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('active', true)
            ->orderBy('sort_order')
            ->withCount(['products' => fn($q) => $q->where('active', true)])
            ->get();

        $featuredProducts = Product::where('active', true)
            ->with('category')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
