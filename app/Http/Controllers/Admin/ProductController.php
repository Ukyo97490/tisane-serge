<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('categorie')) {
            $query->where('category_id', $request->categorie);
        }

        $products   = $query->orderBy('sort_order')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'benefits'    => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
            'active'      => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        $data['slug']   = Str::slug($data['name']);
        $data['active'] = $request->boolean('active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'benefits'    => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'unit'        => 'required|string|max:50',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
            'active'      => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        $data['active'] = $request->boolean('active');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return back()->with('success', 'Produit supprimé.');
    }
}
