@extends('layouts.app')

@section('title', 'Accueil')
@section('meta_description', 'Tisanes, miels, sirops et plantes artisanaux de la Réunion. Commandez en ligne et retirez sur un de nos points de vente.')

@section('content')

{{-- Hero --}}
<section class="hero-pattern bg-cream-100 py-16 md:py-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="md:grid md:grid-cols-2 md:gap-12 items-center">
            <div>
                <p class="text-herb-600 font-semibold text-sm tracking-widest uppercase mb-3">Produits naturels de La Réunion</p>
                <h1 class="font-serif text-4xl md:text-5xl font-bold text-earth-800 leading-tight mb-5">
                    Des saveurs d'antan,<br>
                    <span class="text-herb-600">cultivées avec soin</span>
                </h1>
                <p class="text-earth-600 text-lg leading-relaxed mb-8">
                    Tisanes, miels, sirops et plantes artisanaux, récoltés et préparés dans le respect des traditions réunionnaises. Commandez en ligne et retirez sur place.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('shop.index') }}" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Découvrir la boutique
                    </a>
                    <a href="#comment-ca-marche" class="btn-outline">Comment ca marche</a>
                </div>
            </div>
            <div class="hidden md:flex justify-center mt-10 md:mt-0">
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
<section class="py-14 max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-10">
        <h2 class="section-title mb-2">Nos gammes de produits</h2>
        <p class="text-earth-500">De la plante au bocal, des préparations soigneusement élaborées</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        @foreach($categories as $category)
        <a href="{{ route('shop.index', ['categorie' => $category->slug]) }}"
           class="group bg-white rounded-xl p-6 text-center shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
            <div class="text-4xl mb-3">{{ $category->icon ?? '🌿' }}</div>
            <h3 class="font-serif font-semibold text-earth-800 group-hover:text-herb-600 transition-colors">{{ $category->name }}</h3>
            <p class="text-earth-400 text-sm mt-1">{{ $category->products_count }} produit{{ $category->products_count > 1 ? 's' : '' }}</p>
        </a>
        @endforeach
    </div>
</section>

{{-- Produits vedettes --}}
@if($featuredProducts->count())
<section class="py-12 bg-cream-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="section-title mb-1">Nos produits</h2>
                <p class="text-earth-500">Une sélection de nos meilleures préparations</p>
            </div>
            <a href="{{ route('shop.index') }}" class="text-herb-600 font-semibold text-sm hover:underline hidden sm:inline">Voir tout</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($featuredProducts as $product)
            <a href="{{ route('shop.show', $product->slug) }}" class="product-card group">
                <div class="aspect-square bg-cream-100 overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl">
                            {{ $product->category->icon ?? '🌿' }}
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <p class="text-herb-600 text-xs font-semibold uppercase tracking-wide mb-1">{{ $product->category->name }}</p>
                    <h3 class="font-serif font-semibold text-earth-800 group-hover:text-herb-600 transition-colors leading-snug">{{ $product->name }}</h3>
                    <div class="flex items-center justify-between mt-3">
                        <span class="font-bold text-earth-800">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                        <span class="text-xs text-earth-400">/ {{ $product->unit }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('shop.index') }}" class="btn-primary">Voir tous nos produits</a>
        </div>
    </div>
</section>
@endif

{{-- Comment ca marche --}}
<section id="comment-ca-marche" class="py-16 max-w-6xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-12">
        <h2 class="section-title mb-2">Comment ca marche</h2>
        <p class="text-earth-500">Commandez facilement en ligne, payez sur place</p>
    </div>

    <div class="grid md:grid-cols-4 gap-6">
        @foreach([
            ['icon' => '🛒', 'step' => '1', 'title' => 'Choisissez vos produits', 'desc' => 'Parcourez notre boutique et ajoutez vos produits favoris au panier.'],
            ['icon' => '📍', 'step' => '2', 'title' => 'Choisissez un point de retrait', 'desc' => 'Sélectionnez le stand le plus proche de chez vous et un créneau horaire.'],
            ['icon' => '📧', 'step' => '3', 'title' => 'Confirmation par email', 'desc' => 'Vous recevez une confirmation et des rappels avant votre retrait.'],
            ['icon' => '💶', 'step' => '4', 'title' => 'Payez sur place', 'desc' => 'Récupérez votre commande et réglez directement au vendeur.'],
        ] as $item)
        <div class="text-center p-6 bg-white rounded-xl shadow-sm">
            <div class="w-12 h-12 bg-herb-100 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                {{ $item['icon'] }}
            </div>
            <div class="text-xs font-bold text-herb-500 mb-2">ETAPE {{ $item['step'] }}</div>
            <h3 class="font-serif font-semibold text-earth-800 mb-2">{{ $item['title'] }}</h3>
            <p class="text-earth-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- Bandeau valeurs --}}
<section class="bg-herb-800 text-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @foreach([
                ['icon' => '🌱', 'label' => 'Naturel', 'sub' => 'Sans additifs'],
                ['icon' => '🏝️', 'label' => 'Réunionnais', 'sub' => 'Produit local'],
                ['icon' => '🤝', 'label' => 'Artisanal', 'sub' => 'Fait main'],
                ['icon' => '♻️', 'label' => 'Durable', 'sub' => 'Eco-responsable'],
            ] as $val)
            <div>
                <div class="text-3xl mb-2">{{ $val['icon'] }}</div>
                <div class="font-serif font-semibold text-white">{{ $val['label'] }}</div>
                <div class="text-herb-300 text-sm">{{ $val['sub'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
