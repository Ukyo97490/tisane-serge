@extends('layouts.app')

@php
    $seoTitle   = $product->name . ' — ' . $product->category->name . ' | Tisane Lontan';
    $seoDesc    = \Illuminate\Support\Str::limit($product->description ?? $product->name . ', produit artisanal de La Réunion.', 155);
    $productUrl = route('shop.show', $product->slug);
    $imageUrl   = $product->image ? asset('storage/' . $product->image) : asset('images/placeholder.svg');
@endphp

@section('seo_title', $seoTitle)
@section('seo_description', $seoDesc)
@section('canonical', $productUrl)
@section('og_type', 'product')
@section('og_image', $imageUrl)
@section('og_image_alt', $product->name . ' — Tisane Lontan')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Product",
      "@id": "{{ $productUrl }}#product",
      "name": "{{ $product->name }}",
      "description": "{{ $product->description }}",
      "image": ["{{ $imageUrl }}"],
      "url": "{{ $productUrl }}",
      "sku": "{{ $product->id }}",
      "category": "{{ $product->category->name }}",
      "brand": {
        "@type": "Brand",
        "name": "Tisane Lontan"
      },
      "offers": {
        "@type": "Offer",
        "url": "{{ $productUrl }}",
        "priceCurrency": "EUR",
        "price": "{{ $product->price }}",
        "priceValidUntil": "{{ now()->addYear()->toDateString() }}",
        "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition",
        "seller": {
          "@type": "Organization",
          "name": "Tisane Lontan"
        }
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Accueil",
          "item": "{{ url('/') }}"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Boutique",
          "item": "{{ route('shop.index') }}"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "{{ $product->category->name }}",
          "item": "{{ route('shop.index', ['categorie' => $product->category->slug]) }}"
        },
        {
          "@type": "ListItem",
          "position": 4,
          "name": "{{ $product->name }}",
          "item": "{{ $productUrl }}"
        }
      ]
    }
  ]
}
</script>
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    {{-- Fil d'Ariane --}}
    <nav aria-label="Fil d'Ariane" class="mb-8">
        <ol class="flex flex-wrap items-center gap-2 text-sm text-earth-400" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('home') }}" class="hover:text-herb-600 transition-colors" itemprop="item"><span itemprop="name">Accueil</span></a>
                <meta itemprop="position" content="1">
            </li>
            <li aria-hidden="true">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('shop.index') }}" class="hover:text-herb-600 transition-colors" itemprop="item"><span itemprop="name">Boutique</span></a>
                <meta itemprop="position" content="2">
            </li>
            <li aria-hidden="true">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('shop.index', ['categorie' => $product->category->slug]) }}" class="hover:text-herb-600 transition-colors" itemprop="item"><span itemprop="name">{{ $product->category->name }}</span></a>
                <meta itemprop="position" content="3">
            </li>
            <li aria-hidden="true">/</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="text-earth-600" itemprop="name" aria-current="page">{{ $product->name }}</span>
                <meta itemprop="position" content="4">
            </li>
        </ol>
    </nav>

    <article itemscope itemtype="https://schema.org/Product">
        <meta itemprop="name" content="{{ $product->name }}">
        <meta itemprop="description" content="{{ $product->description }}">
        <link itemprop="url" href="{{ $productUrl }}">

        <div class="md:grid md:grid-cols-2 md:gap-12">

            {{-- Image --}}
            <div class="mb-8 md:mb-0">
                <div class="aspect-square bg-cream-100 rounded-2xl overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }} — {{ $product->category->name }} artisanal de La Réunion"
                             class="w-full h-full object-cover"
                             width="600" height="600"
                             itemprop="image">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-9xl" role="img" aria-label="{{ $product->category->name }} Tisane Lontan">
                            {{ $product->category->icon ?? '🌿' }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Infos --}}
            <div>
                <p class="text-herb-600 font-semibold text-sm uppercase tracking-widest mb-2" itemprop="category">{{ $product->category->name }}</p>
                <h1 class="font-serif text-3xl md:text-4xl font-bold text-earth-800 mb-4" itemprop="name">{{ $product->name }}</h1>

                <div class="flex items-baseline gap-2 mb-6" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <meta itemprop="priceCurrency" content="EUR">
                    <link itemprop="availability" href="{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}">
                    <link itemprop="itemCondition" href="https://schema.org/NewCondition">
                    <span class="text-3xl font-bold text-earth-800" itemprop="price" content="{{ $product->price }}">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                    <span class="text-earth-400">/ {{ $product->unit }}</span>
                </div>

                @if($product->description)
                <div class="text-earth-600 mb-6 leading-relaxed" itemprop="description">
                    <p>{{ $product->description }}</p>
                </div>
                @endif

                @if($product->benefits)
                <div class="bg-herb-50 rounded-xl p-5 mb-6">
                    <h2 class="font-serif font-semibold text-herb-800 mb-2 flex items-center gap-2 text-base">
                        <span aria-hidden="true">🌱</span> Bienfaits et vertus
                    </h2>
                    <p class="text-herb-700 text-sm leading-relaxed">{{ $product->benefits }}</p>
                </div>
                @endif

                @if($product->stock <= 0)
                    <div class="alert alert-error mb-5" role="alert">Ce produit est actuellement épuisé.</div>
                @elseif($product->stock <= 5)
                    <div class="alert alert-warning mb-5" role="status">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Plus que {{ $product->stock }} en stock.
                    </div>
                @endif

                <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-4">
                    @csrf
                    <fieldset class="flex items-center border border-earth-200 rounded-lg overflow-hidden" aria-label="Quantité">
                        <legend class="sr-only">Quantité</legend>
                        <button type="button" onclick="changeQty(-1)" class="px-3 py-2.5 text-earth-600 hover:bg-cream-100 transition-colors font-bold text-lg" aria-label="Diminuer la quantité">-</button>
                        <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ max(1, $product->stock) }}"
                               class="w-14 text-center border-x border-earth-200 py-2.5 font-semibold focus:outline-none"
                               aria-label="Quantité">
                        <button type="button" onclick="changeQty(1)" class="px-3 py-2.5 text-earth-600 hover:bg-cream-100 transition-colors font-bold text-lg" aria-label="Augmenter la quantité">+</button>
                    </fieldset>
                    <button type="submit" class="btn-primary flex-1 justify-center"
                            {{ $product->stock <= 0 ? 'disabled aria-disabled="true"' : '' }}
                            aria-label="Ajouter {{ $product->name }} au panier">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Ajouter au panier
                    </button>
                </form>

                <dl class="mt-6 pt-6 border-t border-cream-200 grid grid-cols-2 gap-4 text-sm text-earth-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-herb-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div><dt class="sr-only">Livraison</dt><dd>Retrait sur stand uniquement</dd></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-herb-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <div><dt class="sr-only">Paiement</dt><dd>Paiement sur place</dd></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-herb-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <div><dt class="sr-only">Origine</dt><dd>Produit de La Réunion</dd></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-herb-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        <div><dt class="sr-only">Fabrication</dt><dd>Artisanal, sans additifs</dd></div>
                    </div>
                </dl>
            </div>
        </div>
    </article>

    {{-- Produits similaires --}}
    @if($related->count())
    <section class="mt-16" aria-labelledby="related-title">
        <h2 id="related-title" class="section-title mb-6">Vous aimerez aussi</h2>
        <ul class="grid grid-cols-2 md:grid-cols-4 gap-5 list-none p-0">
            @foreach($related as $rel)
            <li>
                <article class="product-card group" itemscope itemtype="https://schema.org/Product">
                    <a href="{{ route('shop.show', $rel->slug) }}" itemprop="url" class="block">
                        <div class="aspect-square bg-cream-100 overflow-hidden">
                            @if($rel->image)
                                <img src="{{ asset('storage/' . $rel->image) }}"
                                     alt="{{ $rel->name }} — {{ $rel->category->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy" width="300" height="300"
                                     itemprop="image">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl" role="img" aria-label="{{ $rel->category->name }}">{{ $rel->category->icon ?? '🌿' }}</div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-serif font-semibold text-earth-800 group-hover:text-herb-600 transition-colors text-sm" itemprop="name">{{ $rel->name }}</h3>
                            <span class="font-bold text-earth-700 text-sm" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="EUR">
                                <span itemprop="price" content="{{ $rel->price }}">{{ number_format($rel->price, 2, ',', ' ') }} €</span>
                            </span>
                        </div>
                    </a>
                </article>
            </li>
            @endforeach
        </ul>
    </section>
    @endif
</div>

@push('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    const max = parseInt(input.max) || 99;
    const val = parseInt(input.value) + delta;
    input.value = Math.max(1, Math.min(max, val));
}
</script>
@endpush
@endsection
