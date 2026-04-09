<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        if (!$product->active) {
            return back()->with('error', 'Ce produit n\'est plus disponible.');
        }

        $cart = session('cart', []);
        $key  = (string) $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => (float) $product->price,
                'unit'     => $product->unit,
                'image'    => $product->image,
                'quantity' => $request->quantity,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', '"' . $product->name . '" ajouté au panier.');
    }

    public function update(Request $request, int $productId)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:99']);

        $cart = session('cart', []);
        $key  = (string) $productId;

        if ($request->quantity === 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(int $productId)
    {
        $cart = session('cart', []);
        unset($cart[(string) $productId]);
        session(['cart' => $cart]);

        return back()->with('success', 'Article retiré du panier.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('shop.index')->with('success', 'Panier vidé.');
    }
}
