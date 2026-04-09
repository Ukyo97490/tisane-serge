@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', $product->description)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    {{-- Fil d'Ariane --}}
    <nav class="flex items-center gap-2 text-sm text-earth-400 mb-8">
        <a href="{{ route('home') }}" class="hover:text-herb-600 transition-colors">Accueil</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-herb-600 transition-colors">Boutique</a>
        <span>/</span>
        <a href="{{ route('shop.index', ['categorie' => $product->category->slug]) }}" class="hover:text-herb-600 transition-colors">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-earth-600">{{ $product->name }}</span>
    </nav>

    <div class="md:grid md:grid-cols-2 md:gap-12">

        {{-- Image --}}
        <div class="mb-8 md:mb-0">
            <div class="aspect-square bg-cream-100 rounded-2xl overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-9xl">
                        {{ $product->category->icon ?? '🌿' }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Infos --}}
        <div>
            <p class="text-herb-600 font-semibold text-sm uppercase tracking-widest mb-2">{{ $product->category->name }}</p>
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-earth-800 mb-4">{{ $product->name }}</h1>

            <div class="flex items-baseline gap-2 mb-6">
                <span class="text-3xl font-bold text-earth-800">{{ number_format($product->price, 2, ',', ' ') }} €</span>
                <span class="text-earth-400">/ {{ $product->unit }}</span>
            </div>

            @if($product->description)
            <div class="prose prose-earth text-earth-600 mb-6 leading-relaxed">
                <p>{{ $product->description }}</p>
            </div>
            @endif

            @if($product->benefits)
            <div class="bg-herb-50 rounded-xl p-5 mb-6">
                <h3 class="font-serif font-semibold text-herb-800 mb-2 flex items-center gap-2">
                    <span>🌱</span> Bienfaits
                </h3>
                <p class="text-herb-700 text-sm leading-relaxed">{{ $product->benefits }}</p>
            </div>
            @endif

            @if($product->stock <= 0)
                <div class="alert alert-error mb-5">Ce produit est actuellement épuisé.</div>
            @elseif($product->stock <= 5)
                <div class="alert alert-warning mb-5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Plus que {{ $product->stock }} en stock.
                </div>
            @endif

            <form method="POST" action="{{ route('cart.add', $product) }}" class="flex items-center gap-4">
                @csrf
                <div class="flex items-center border border-earth-200 rounded-lg overflow-hidden">
                    <button type="button" onclick="changeQty(-1)" class="px-3 py-2.5 text-earth-600 hover:bg-cream-100 transition-colors font-bold text-lg">-</button>
                    <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ max(1, $product->stock) }}"
                           class="w-14 text-center border-x border-earth-200 py-2.5 font-semibold focus:outline-none">
                    <button type="button" onclick="changeQty(1)" class="px-3 py-2.5 text-earth-600 hover:bg-cream-100 transition-colors font-bold text-lg">+</button>
                </div>
                <button type="submit" class="btn-primary flex-1 justify-center" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    Ajouter au panier
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-cream-200 grid grid-cols-2 gap-4 text-sm text-earth-500">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-herb-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Retrait sur stand uniquement
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-herb-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Paiement sur place
                </div>
            </div>
        </div>
    </div>

    {{-- Produits similaires --}}
    @if($related->count())
    <div class="mt-16">
        <h2 class="section-title mb-6">Vous aimerez aussi</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($related as $rel)
            <a href="{{ route('shop.show', $rel->slug) }}" class="product-card group">
                <div class="aspect-square bg-cream-100 overflow-hidden">
                    @if($rel->image)
                        <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">{{ $rel->category->icon ?? '🌿' }}</div>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-serif font-semibold text-earth-800 group-hover:text-herb-600 transition-colors text-sm">{{ $rel->name }}</h3>
                    <span class="font-bold text-earth-700 text-sm">{{ number_format($rel->price, 2, ',', ' ') }} €</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
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
