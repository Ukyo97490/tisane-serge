@extends('layouts.app')

@section('seo_title', 'Mon panier — Tisane Lontan')
@section('seo_description', 'Votre panier de produits artisanaux Tisane Lontan.')
@section('robots', 'noindex, follow')
@section('canonical', route('cart.index'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="section-title mb-8">Mon panier</h1>

    @if(empty($cart))
        <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="font-serif text-xl text-earth-700 mb-2">Votre panier est vide</h2>
            <p class="text-earth-400 mb-6">Découvrez nos produits artisanaux et ajoutez-les à votre panier.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary">Découvrir la boutique</a>
        </div>
    @else
        <div class="space-y-4 mb-8">
            @foreach($cart as $item)
            <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-5">
                {{-- Image --}}
                <div class="w-16 h-16 bg-cream-100 rounded-lg overflow-hidden shrink-0">
                    @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl">🌿</div>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="flex-1 min-w-0">
                    <h3 class="font-serif font-semibold text-earth-800 truncate">{{ $item['name'] }}</h3>
                    <p class="text-earth-400 text-sm">{{ number_format($item['price'], 2, ',', ' ') }} € / {{ $item['unit'] }}</p>
                </div>

                {{-- Quantité --}}
                <form method="POST" action="{{ route('cart.update', $item['id']) }}" class="flex items-center gap-1">
                    @csrf
                    <div class="flex items-center border border-earth-200 rounded-lg overflow-hidden">
                        <button type="button" onclick="this.form.querySelector('input').value = Math.max(0, parseInt(this.form.querySelector('input').value)-1); this.form.submit()"
                                class="px-2.5 py-1.5 text-earth-600 hover:bg-cream-100 font-bold">-</button>
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="99"
                               class="w-10 text-center py-1.5 font-semibold focus:outline-none border-x border-earth-200 text-sm">
                        <button type="button" onclick="this.form.querySelector('input').value = Math.min(99, parseInt(this.form.querySelector('input').value)+1); this.form.submit()"
                                class="px-2.5 py-1.5 text-earth-600 hover:bg-cream-100 font-bold">+</button>
                    </div>
                </form>

                {{-- Sous-total --}}
                <div class="text-right shrink-0">
                    <span class="font-bold text-earth-800">{{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €</span>
                </div>

                {{-- Supprimer --}}
                <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                    @csrf
                    <button type="submit" class="text-earth-300 hover:text-red-500 transition-colors p-1" title="Supprimer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        {{-- Total + actions --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-earth-600 font-medium">Sous-total ({{ collect($cart)->sum('quantity') }} article{{ collect($cart)->sum('quantity') > 1 ? 's' : '' }})</span>
                <span class="font-bold text-xl text-earth-800">{{ number_format($total, 2, ',', ' ') }} €</span>
            </div>
            <div class="bg-herb-50 rounded-lg p-3 mb-5 text-sm text-herb-700 flex items-start gap-2">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Paiement sur place lors du retrait. Aucun paiement en ligne n'est requis.
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('shop.index') }}" class="btn-outline justify-center">Continuer les achats</a>
                <a href="{{ route('checkout.index') }}" class="btn-primary flex-1 justify-center">
                    Commander
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
