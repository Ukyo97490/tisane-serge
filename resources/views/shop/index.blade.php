@extends('layouts.app')

@php
    $pageTitle = $currentCategory
        ? $currentCategory->name . ' artisanaux de La Réunion | Tisane Lontan'
        : 'Boutique — Tisanes, Miels, Sirops & Plantes | Tisane Lontan';

    $pageDesc = $currentCategory
        ? ($currentCategory->description
            ? \Illuminate\Support\Str::limit($currentCategory->description, 155)
            : 'Découvrez nos ' . strtolower($currentCategory->name) . ' artisanaux de La Réunion. Commandez en ligne, retirez sur nos marchés locaux.')
        : 'Tous nos produits artisanaux de La Réunion : tisanes, miels, sirops et plantes médicinales. Commandez en ligne, paiement et retrait sur place.';

    $canonicalUrl = $currentCategory
        ? route('shop.index', ['categorie' => $currentCategory->slug])
        : route('shop.index');
@endphp

@section('seo_title', $pageTitle)
@section('seo_description', $pageDesc)
@section('canonical', $canonicalUrl)
@section('og_type', 'website')

@section('pagination_links')
    @if($products->currentPage() > 1)
        <link rel="prev" href="{{ $products->previousPageUrl() }}">
    @endif
    @if($products->hasMorePages())
        <link rel="next" href="{{ $products->nextPageUrl() }}">
    @endif
@endsection

@section('json_ld')
@if($products->count())
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "ItemList",
  "name": "{{ $currentCategory ? $currentCategory->name : 'Tous nos produits' }}",
  "description": "{{ $pageDesc }}",
  "numberOfItems": {{ $products->total() }},
  "itemListElement": [
    @foreach($products as $i => $product)
    {
      "@type": "ListItem",
      "position": {{ ($products->currentPage() - 1) * $products->perPage() + $i + 1 }},
      "item": {
        "@type": "Product",
        "name": "{{ $product->name }}",
        "url": "{{ route('shop.show', $product->slug) }}",
        "image": "{{ $product->image ? asset('storage/' . $product->image) : asset('images/placeholder.svg') }}",
        "description": "{{ \Illuminate\Support\Str::limit($product->description, 100) }}",
        "offers": {
          "@type": "Offer",
          "priceCurrency": "EUR",
          "price": "{{ $product->price }}",
          "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
        }
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    {{-- Fil d'Ariane --}}
    <nav aria-label="Fil d'Ariane" class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-earth-400" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('home') }}" class="hover:text-herb-600 transition-colors" itemprop="item">
                    <span itemprop="name">Accueil</span>
                </a>
                <meta itemprop="position" content="1">
            </li>
            <li aria-hidden="true"><span>/</span></li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if($currentCategory)
                    <a href="{{ route('shop.index') }}" class="hover:text-herb-600 transition-colors" itemprop="item">
                        <span itemprop="name">Boutique</span>
                    </a>
                    <meta itemprop="position" content="2">
                @else
                    <span class="text-earth-600" itemprop="name" aria-current="page">Boutique</span>
                    <meta itemprop="position" content="2">
                @endif
            </li>
            @if($currentCategory)
            <li aria-hidden="true"><span>/</span></li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="text-earth-600" itemprop="name" aria-current="page">{{ $currentCategory->name }}</span>
                <meta itemprop="position" content="3">
            </li>
            @endif
        </ol>
    </nav>

    {{-- En-tête --}}
    <header class="mb-8">
        <h1 class="section-title mb-1">{{ $currentCategory ? $currentCategory->name : 'Notre boutique' }}</h1>
        <p class="text-earth-500">
            @if($currentCategory && $currentCategory->description)
                {{ $currentCategory->description }}
            @else
                Tisanes, miels, sirops et plantes artisanaux de La Réunion
            @endif
        </p>
    </header>

    <div class="flex flex-col md:flex-row gap-8">

        {{-- Sidebar filtres --}}
        <aside class="md:w-56 shrink-0" aria-label="Filtres et catégories">
            <div class="card">
                <h2 class="font-serif font-semibold text-earth-700 mb-4 text-base">Catégories</h2>
                <ul class="space-y-1 list-none p-0">
                    <li>
                        <a href="{{ route('shop.index') }}"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors {{ !$currentCategory ? 'bg-herb-100 text-herb-700 font-semibold' : 'text-earth-600 hover:bg-cream-100' }}"
                           {{ !$currentCategory ? 'aria-current="page"' : '' }}>
                            Tout voir
                        </a>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('shop.index', ['categorie' => $cat->slug]) }}"
                           class="block px-3 py-2 rounded-lg text-sm transition-colors {{ $currentCategory?->id === $cat->id ? 'bg-herb-100 text-herb-700 font-semibold' : 'text-earth-600 hover:bg-cream-100' }}"
                           {{ $currentCategory?->id === $cat->id ? 'aria-current="page"' : '' }}>
                            <span aria-hidden="true">{{ $cat->icon ?? '' }}</span> {{ $cat->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="card mt-4">
                <form method="GET" action="{{ route('shop.index') }}" role="search">
                    @if($currentCategory)
                        <input type="hidden" name="categorie" value="{{ $currentCategory->slug }}">
                    @endif
                    <label class="form-label" for="search-q">Rechercher un produit</label>
                    <input type="search" name="q" id="search-q" value="{{ request('q') }}"
                           class="form-input" placeholder="Tisane, miel, sirop..."
                           aria-label="Rechercher dans la boutique">
                    <button type="submit" class="btn-primary w-full mt-3 justify-center">Rechercher</button>
                </form>
            </div>
        </aside>

        {{-- Grille produits --}}
        <div class="flex-1">
            @if($products->isEmpty())
                <div class="text-center py-16 text-earth-400">
                    <div class="text-5xl mb-4" role="img" aria-label="Aucun produit">🌿</div>
                    <h2 class="font-serif text-lg">Aucun produit trouvé</h2>
                    <p class="text-sm mt-1">Essayez une autre catégorie ou modifiez votre recherche.</p>
                    <a href="{{ route('shop.index') }}" class="btn-outline mt-5 inline-flex">Voir tous les produits</a>
                </div>
            @else
                <p class="text-sm text-earth-400 mb-4">
                    {{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} trouvé{{ $products->total() > 1 ? 's' : '' }}
                    @if(request('q'))<span> pour <strong>« {{ request('q') }} »</strong></span>@endif
                </p>
                <ul class="grid grid-cols-2 lg:grid-cols-3 gap-5 list-none p-0">
                    @foreach($products as $product)
                    <li>
                        <article class="product-card h-full flex flex-col" itemscope itemtype="https://schema.org/Product">
                            <link itemprop="url" href="{{ route('shop.show', $product->slug) }}">
                            <a href="{{ route('shop.show', $product->slug) }}" class="block flex-1">
                                <div class="aspect-square bg-cream-100 overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }} — {{ $product->category->name }}"
                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                             loading="lazy" width="400" height="400"
                                             itemprop="image">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-5xl" role="img" aria-label="{{ $product->category->name }}">
                                            {{ $product->category->icon ?? '🌿' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4 flex-1">
                                    <p class="text-herb-600 text-xs font-semibold uppercase tracking-wide mb-1" itemprop="category">{{ $product->category->name }}</p>
                                    <h2 class="font-serif font-semibold text-earth-800 hover:text-herb-600 transition-colors leading-snug text-base" itemprop="name">{{ $product->name }}</h2>
                                    @if($product->description)
                                        <p class="text-earth-500 text-xs mt-1 leading-relaxed line-clamp-2" itemprop="description">{{ $product->description }}</p>
                                    @endif
                                </div>
                            </a>
                            <div class="px-4 pb-4" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="EUR">
                                <meta itemprop="url" content="{{ route('shop.show', $product->slug) }}">
                                <link itemprop="availability" href="{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}">
                                <div class="flex items-center justify-between mt-2 mb-3">
                                    <div>
                                        <span class="font-bold text-earth-800 text-lg" itemprop="price" content="{{ $product->price }}">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                        <span class="text-xs text-earth-400 ml-1">/ {{ $product->unit }}</span>
                                    </div>
                                    @if($product->stock <= 0)
                                        <span class="text-xs text-red-500 font-medium">Epuisé</span>
                                    @elseif($product->stock <= 5)
                                        <span class="text-xs text-amber-600 font-medium">Peu de stock</span>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('cart.add', $product) }}">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn-primary w-full justify-center btn-sm"
                                        {{ $product->stock <= 0 ? 'disabled aria-disabled="true"' : '' }}
                                        aria-label="Ajouter {{ $product->name }} au panier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        {{ $product->stock <= 0 ? 'Epuisé' : 'Ajouter au panier' }}
                                    </button>
                                </form>
                            </div>
                        </article>
                    </li>
                    @endforeach
                </ul>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
