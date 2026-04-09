<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $categories = Category::where('active', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $products = Product::where('active', true)
            ->with('category')
            ->orderBy('updated_at', 'desc')
            ->get();

        $content = view('sitemap', compact('categories', 'products'));

        return response($content, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8');
    }
}
