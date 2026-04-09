@extends('layouts.app')

@section('seo_title', 'Commande confirmée — Tisane Lontan')
@section('seo_description', 'Votre commande Tisane Lontan est confirmée.')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-14">

    {{-- Bannière succès --}}
    <div class="text-center mb-10">
        <div class="w-20 h-20 bg-herb-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-herb-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="font-serif text-3xl font-bold text-earth-800 mb-2">Commande confirmée !</h1>
        <p class="text-earth-500">Un email de confirmation a été envoyé à <strong>{{ $order->customer_email }}</strong>.</p>
    </div>

    {{-- Carte récapitulatif --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">

        {{-- En-tête commande --}}
        <div class="bg-herb-700 text-white px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-herb-300 text-sm">Référence de commande</p>
                    <p class="font-mono font-bold text-xl">{{ $order->reference }}</p>
                </div>
                <span class="badge badge-en_attente text-sm px-3 py-1.5">{{ $order->status_label }}</span>
            </div>
        </div>

        <div class="px-6 py-5 space-y-5">

            {{-- Articles --}}
            <div>
                <h3 class="font-serif font-semibold text-earth-700 mb-3">Articles commandés</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-earth-700">{{ $item->product_name }} <span class="text-earth-400">x{{ $item->quantity }}</span></span>
                        <span class="font-semibold text-earth-800">{{ number_format($item->subtotal, 2, ',', ' ') }} €</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-cream-200 mt-3 pt-3 flex justify-between font-bold text-earth-800">
                    <span>Total à payer sur place</span>
                    <span class="text-lg">{{ number_format($order->total, 2, ',', ' ') }} €</span>
                </div>
            </div>

            {{-- Point de retrait --}}
            <div class="border-t border-cream-200 pt-5">
                <h3 class="font-serif font-semibold text-earth-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-herb-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Point de retrait
                </h3>
                <p class="font-semibold text-earth-800">{{ $order->pickupPoint->name }}</p>
                <p class="text-earth-500 text-sm">{{ $order->pickupPoint->full_address }}</p>
            </div>

            {{-- Date et heure --}}
            <div class="border-t border-cream-200 pt-4 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-earth-400 text-xs mb-1">Date de retrait</p>
                    <p class="font-semibold text-earth-800">
                        {{ \Carbon\Carbon::parse($order->pickup_date)->locale('fr')->isoFormat('dddd D MMMM Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-earth-400 text-xs mb-1">Heure prévue</p>
                    <p class="font-semibold text-earth-800">{{ substr($order->pickup_time, 0, 5) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Rappels --}}
    <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 mb-6">
        <h3 class="font-serif font-semibold text-amber-800 mb-2 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Rappels automatiques
        </h3>
        <ul class="text-amber-700 text-sm space-y-1">
            <li>Vous recevrez un rappel par email <strong>24h avant</strong> votre retrait.</li>
            <li>Un second rappel vous sera envoyé <strong>1h avant</strong> l'heure choisie.</li>
        </ul>
    </div>

    <div class="text-center">
        <a href="{{ route('shop.index') }}" class="btn-outline">Continuer mes achats</a>
    </div>
</div>
@endsection
