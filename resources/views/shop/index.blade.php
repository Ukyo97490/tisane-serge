@extends('layouts.app')

@section('title', $currentCategory ? $currentCategory->name : 'Boutique')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    {{-- En-tête --}}
    <div class="mb-8">
        <h1 class="section-title mb-1">{{ $currentCategory ? $currentCategory->name : 'Notre boutique' }}</h1>
        <p class="text-earth-500">
            @if($currentCategory && $currentCategory->description)
                {{ $currentCategory->description }}
            @else
                Tisanes, miels, sirops et plantes artisanaux de La Réunion
            @endif
        </p>
    </div>

    <div class="flex flex-col md:flex-row gap-8">

        {{-- Sidebar filtres --}}
        <aside class="md:w-56 shrink-0">
            <div class="card">
                <h3 class="font-serif font-semibold text-earth-700 mb-4">Catégories</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('shop.index') }}"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors {{ !$currentCategory ? 'bg-herb-100 text-herb-700 font-semibold' : 'text-earth-600 hover:bg-cream-100' }}">
                            Tout voir
                        </a>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('shop.index', ['categorie' => $cat->slug]) }}"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors {{ $currentCategory?->id === $cat->id ? 'bg-herb-100 text-herb-700 font-semibold' : 'text-earth-600 hover:bg-cream-100' }}">
                            {{ $cat->icon ?? '' }} {{ $cat->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Recherche --}}
            <div class="card mt-4">
                <form method="GET" action="{{ route('shop.index') }}">
                    @if($currentCategory)
                        <input type="hidden" name="categorie" value="{{ $currentCategory->slug }}">
                    @endif
                    <label class="form-label">Rechercher</label>
                    <div class="flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}"
                               class="form-input" placeholder="Nom du produit...">
                    </div>
                    <button type="submit" class="btn-primary w-full mt-3 justify-center">Rechercher</button>
                </form>
            </div>
        </aside>

        {{-- Grille produits --}}
        <div class="flex-1">
            @if($products->isEmpty())
                <div class="text-center py-16 text-earth-400">
                    <div class="text-5xl mb-4">🌿</div>
                    <p class="font-serif text-lg">Aucun produit trouvé</p>
                    <p class="text-sm mt-1">Essayez une autre catégorie ou modifiez votre recherche.</p>
                    <a href="{{ route('shop.index') }}" class="btn-outline mt-5 inline-flex">Voir tous les produits</a>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($products as $product)
                    <div class="product-card">
                        <a href="{{ route('shop.show', $product->slug) }}">
                            <div class="aspect-square bg-cream-100 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl">
                                        {{ $product->category->icon ?? '🌿' }}
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <p class="text-herb-600 text-xs font-semibold uppercase tracking-wide mb-1">{{ $product->category->name }}</p>
                            <a href="{{ route('shop.show', $product->slug) }}">
                                <h3 class="font-serif font-semibold text-earth-800 hover:text-herb-600 transition-colors leading-snug">{{ $product->name }}</h3>
                            </a>
                            @if($product->description)
                                <p class="text-earth-500 text-xs mt-1 leading-relaxed line-clamp-2">{{ $product->description }}</p>
                            @endif
                            <div class="flex items-center justify-between mt-3">
                                <div>
                                    <span class="font-bold text-earth-800 text-lg">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                    <span class="text-xs text-earth-400 ml-1">/ {{ $product->unit }}</span>
                                </div>
                                @if($product->stock <= 0)
                                    <span class="text-xs text-red-500 font-medium">Epuisé</span>
                                @elseif($product->stock <= 5)
                                    <span class="text-xs text-amber-600 font-medium">Peu de stock</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn-primary w-full justify-center btn-sm"
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    {{ $product->stock <= 0 ? 'Epuisé' : 'Ajouter' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
