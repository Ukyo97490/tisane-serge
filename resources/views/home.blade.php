@extends('layouts.app')

@section('seo_title', 'Tisane Lontan — Tisanes, Miels & Plantes artisanaux de La Réunion')
@section('seo_description', 'Tisanes, miels, sirops et plantes artisanaux cultivés à La Réunion. Commandez en ligne, retirez sur nos marchés locaux. Saveurs naturelles depuis toujours.')
@section('canonical', route('home'))
@section('og_image_alt', 'Tisane Lontan — Produits naturels de La Réunion')

@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "LocalBusiness",
      "@id": "{{ url('/') }}/#localbusiness",
      "name": "Tisane Lontan",
      "description": "Tisanes, miels, sirops et plantes artisanaux de La Réunion. Vente sur marchés locaux.",
      "url": "{{ url('/') }}",
      "image": "{{ asset('images/og-default.jpg') }}",
      "priceRange": "€",
      "currenciesAccepted": "EUR",
      "paymentAccepted": "Cash, Chèque",
      "areaServed": {
        "@type": "Place",
        "name": "La Réunion"
      },
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Saint-Denis",
        "addressCountry": "RE"
      }
    },
    {
      "@type": "ItemList",
      "name": "Catégories de produits Tisane Lontan",
      "itemListElement": [
        @foreach($categories as $i => $category)
        {
          "@type": "ListItem",
          "position": {{ $i + 1 }},
          "name": "{{ $category->name }}",
          "url": "{{ route('shop.index', ['categorie' => $category->slug]) }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
      ]
    }
  ]
}
</script>
@endsection

@section('content')

{{-- Hero --}}
<section class="hero-pattern bg-cream-100 py-16 md:py-24" aria-labelledby="hero-title">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="md:grid md:grid-cols-2 md:gap-12 items-center">
            <div>
                <p class="text-herb-600 font-semibold text-sm tracking-widest uppercase mb-3">Produits naturels de La Réunion</p>
                <h1 id="hero-title" class="font-serif text-4xl md:text-5xl font-bold text-earth-800 leading-tight mb-5">
                    Des saveurs d'antan,<br>
                    <span class="text-herb-600">cultivées avec soin</span>
                </h1>
                <p class="text-earth-600 text-lg leading-relaxed mb-8">
                    Tisanes, miels, sirops et plantes artisanaux, récoltés et préparés dans le respect des traditions réunionnaises. Commandez en ligne et retirez sur place.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('shop.index') }}" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Découvrir la boutique
                    </a>
                    <a href="#comment-ca-marche" class="btn-outline">Comment ca marche</a>
                </div>
            </div>
            <div class="hidden md:flex justify-center mt-10 md:mt-0" aria-hidden="true">
                <div class="relative">
                    <div class="w-72 h-72 bg-herb-100 rounded-full flex items-center justify-center">
                        <span class="text-9xl">🌿</span>
                    </div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center">
                        <span class="text-3xl">🍯</span>
                    </div>
                    <div class="absolute -bottom-2 -left-6 w-16 h-16 bg-cream-200 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🌸</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Catégories --}}
<section class="py-14 max-w-6xl mx-auto px-4 sm:px-6" aria-labelledby="categories-title">
    <div class="text-center mb-10">
        <h2 id="categories-title" class="section-title mb-2">Nos gammes de produits</h2>
        <p class="text-earth-500">De la plante au bocal, des préparations soigneusement élaborées</p>
    </div>

    <ul class="grid grid-cols-2 md:grid-cols-4 gap-5 list-none p-0">
        @foreach($categories as $category)
        <li>
            <a href="{{ route('shop.index', ['categorie' => $category->slug]) }}"
               class="group bg-white rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all hover:-translate-y-1 flex flex-col items-center">
                <span class="text-4xl mb-3" role="img" aria-label="{{ $category->name }}">{{ $category->icon ?? '🌿' }}</span>
                <h3 class="font-serif font-semibold text-earth-800 group-hover:text-herb-600 transition-colors">{{ $category->name }}</h3>
                <p class="text-earth-400 text-sm mt-1">
                    <span class="sr-only">Nombre de produits :</span>
                    {{ $category->products_count }} produit{{ $category->products_count > 1 ? 's' : '' }}
                </p>
            </a>
        </li>
        @endforeach
    </ul>
</section>

{{-- Produits vedettes --}}
@if($featuredProducts->count())
<section class="py-12 bg-cream-50" aria-labelledby="featured-title">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 id="featured-title" class="section-title mb-1">Nos produits artisanaux</h2>
                <p class="text-earth-500">Une sélection de nos meilleures préparations réunionnaises</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-herb-600 font-semibold text-sm hover:underline hidden sm:inline">Voir tous les produits</a>
        </div>

        <ul class="grid grid-cols-2 md:grid-cols-4 gap-5 list-none p-0">
            @foreach($featuredProducts as $product)
            <li>
                <article class="product-card h-full" itemscope itemtype="https://schema.org/Product">
                    <meta itemprop="name" content="{{ $product->name }}">
                    <meta itemprop="description" content="{{ $product->description }}">
                    <link itemprop="url" href="{{ route('shop.show', $product->slug) }}">
                    <a href="{{ route('shop.show', $product->slug) }}" class="block">
                        <div class="aspect-square bg-cream-100 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }} — {{ $product->category->name }} Tisane Lontan"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     loading="lazy" width="400" height="400"
                                     itemprop="image">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-5xl" role="img" aria-label="{{ $product->category->name }}">
                                    {{ $product->category->icon ?? '🌿' }}
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-herb-600 text-xs font-semibold uppercase tracking-wide mb-1" itemprop="category">{{ $product->category->name }}</p>
                            <h3 class="font-serif font-semibold text-earth-800 hover:text-herb-600 transition-colors leading-snug" itemprop="name">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between mt-3" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="EUR">
                                <meta itemprop="availability" content="{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}">
                                <meta itemprop="url" content="{{ route('shop.show', $product->slug) }}">
                                <span class="font-bold text-earth-800" itemprop="price" content="{{ $product->price }}">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                                <span class="text-xs text-earth-400">/ {{ $product->unit }}</span>
                            </div>
                        </div>
                    </a>
                </article>
            </li>
            @endforeach
        </ul>

        <div class="text-center mt-8">
            <a href="{{ route('shop.index') }}" class="btn-primary">Voir tous nos produits</a>
        </div>
    </div>
</section>
@endif

{{-- Comment ca marche --}}
<section id="comment-ca-marche" class="py-16 max-w-6xl mx-auto px-4 sm:px-6" aria-labelledby="howto-title">
    <div class="text-center mb-12">
        <h2 id="howto-title" class="section-title mb-2">Comment commander</h2>
        <p class="text-earth-500">Commandez facilement en ligne, payez sur place au retrait</p>
    </div>

    <ol class="grid md:grid-cols-4 gap-6 list-none p-0">
        @foreach([
            ['icon' => '🛒', 'step' => '1', 'title' => 'Choisissez vos produits', 'desc' => 'Parcourez notre boutique et ajoutez vos produits favoris au panier.'],
            ['icon' => '📍', 'step' => '2', 'title' => 'Choisissez un point de retrait', 'desc' => 'Sélectionnez le marché le plus proche et un créneau horaire.'],
            ['icon' => '📧', 'step' => '3', 'title' => 'Confirmation par email', 'desc' => 'Vous recevez une confirmation et des rappels avant votre retrait.'],
            ['icon' => '💶', 'step' => '4', 'title' => 'Payez sur place', 'desc' => 'Récupérez votre commande et réglez directement au vendeur.'],
        ] as $item)
        <li class="text-center p-6 bg-white rounded-xl shadow-sm">
            <div class="w-12 h-12 bg-herb-100 rounded-full flex items-center justify-center mx-auto mb-4" role="img" aria-label="{{ $item['title'] }}">
                <span class="text-xl" aria-hidden="true">{{ $item['icon'] }}</span>
            </div>
            <p class="text-xs font-bold text-herb-500 mb-2" aria-hidden="true">ETAPE {{ $item['step'] }}</p>
            <h3 class="font-serif font-semibold text-earth-800 mb-2">{{ $item['title'] }}</h3>
            <p class="text-earth-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
        </li>
        @endforeach
    </ol>
</section>

{{-- Bandeau valeurs --}}
<section class="bg-herb-800 text-white py-10" aria-label="Nos valeurs">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <ul class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center list-none p-0">
            @foreach([
                ['icon' => '🌱', 'label' => 'Naturel', 'sub' => 'Sans additifs'],
                ['icon' => '🏝️', 'label' => 'Réunionnais', 'sub' => 'Produit local'],
                ['icon' => '🤝', 'label' => 'Artisanal', 'sub' => 'Fait main'],
                ['icon' => '♻️', 'label' => 'Durable', 'sub' => 'Eco-responsable'],
            ] as $val)
            <li>
                <div class="text-3xl mb-2" role="img" aria-label="{{ $val['label'] }}">{{ $val['icon'] }}</div>
                <strong class="font-serif font-semibold text-white block">{{ $val['label'] }}</strong>
                <span class="text-herb-300 text-sm">{{ $val['sub'] }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>

@endsection
