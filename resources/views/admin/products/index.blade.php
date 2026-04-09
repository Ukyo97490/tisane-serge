@extends('layouts.admin')
@section('title', 'Produits')
@section('page-title', 'Produits')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-earth-500 text-sm">{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('admin.produits.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouveau produit
    </a>
</div>

{{-- Filtres --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <label class="form-label text-xs">Recherche</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input py-2 text-sm" placeholder="Nom du produit...">
        </div>
        <div class="min-w-40">
            <label class="form-label text-xs">Catégorie</label>
            <select name="categorie" class="form-input py-2 text-sm">
                <option value="">Toutes</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary btn-sm py-2">Filtrer</button>
        @if(request()->hasAny(['q', 'categorie']))
            <a href="{{ route('admin.produits.index') }}" class="btn-outline btn-sm py-2">Réinitialiser</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-earth-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Produit</th>
                    <th class="px-5 py-3 text-left">Catégorie</th>
                    <th class="px-5 py-3 text-right">Prix</th>
                    <th class="px-5 py-3 text-right">Stock</th>
                    <th class="px-5 py-3 text-center">Statut</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="table-row-hover">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-cream-100 rounded-lg overflow-hidden shrink-0">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-lg">{{ $product->category->icon ?? '🌿' }}</div>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-earth-800">{{ $product->name }}</div>
                                <div class="text-earth-400 text-xs">{{ $product->unit }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-earth-600">{{ $product->category->name }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-earth-800">{{ number_format($product->price, 2, ',', ' ') }} €</td>
                    <td class="px-5 py-3 text-right {{ $product->stock <= 5 ? 'text-red-600 font-semibold' : 'text-earth-600' }}">{{ $product->stock }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="{{ $product->active ? 'badge bg-green-100 text-green-700' : 'badge bg-gray-100 text-gray-500' }}">
                            {{ $product->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.produits.edit', $product) }}" class="btn-outline btn-sm py-1">Modifier</a>
                            <form method="POST" action="{{ route('admin.produits.destroy', $product) }}"
                                  onsubmit="return confirm('Supprimer ce produit ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm py-1">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-earth-400">Aucun produit.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
