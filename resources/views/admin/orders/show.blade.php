@extends('layouts.admin')
@section('title', 'Commande ' . $order->reference)
@section('page-title', 'Commande ' . $order->reference)

@section('content')
<div class="max-w-3xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-earth-400 hover:text-herb-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <span class="badge badge-{{ $order->status }} text-sm px-3 py-1">{{ $order->status_label }}</span>
        <span class="text-earth-400 text-sm">Créée le {{ $order->created_at->format('d/m/Y à H:i') }}</span>
    </div>

    <div class="grid md:grid-cols-3 gap-5">

        {{-- Infos client --}}
        <div class="card">
            <h3 class="font-serif font-semibold text-earth-700 mb-3">Client</h3>
            <p class="font-semibold text-earth-800">{{ $order->customer_name }}</p>
            <p class="text-earth-500 text-sm">{{ $order->customer_email }}</p>
            @if($order->customer_phone)
                <p class="text-earth-500 text-sm">{{ $order->customer_phone }}</p>
            @endif
        </div>

        {{-- Point de retrait --}}
        <div class="card">
            <h3 class="font-serif font-semibold text-earth-700 mb-3">Retrait</h3>
            <p class="font-semibold text-earth-800">{{ $order->pickupPoint->name }}</p>
            <p class="text-earth-500 text-sm">{{ $order->pickupPoint->full_address }}</p>
            <div class="mt-2 pt-2 border-t border-cream-200">
                <p class="text-herb-700 font-medium text-sm">
                    {{ \Carbon\Carbon::parse($order->pickup_date)->locale('fr')->isoFormat('dddd D MMMM') }}
                </p>
                <p class="text-herb-600 text-sm">{{ substr($order->pickup_time, 0, 5) }}</p>
            </div>
        </div>

        {{-- Rappels --}}
        <div class="card">
            <h3 class="font-serif font-semibold text-earth-700 mb-3">Rappels envoyés</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-sm {{ $order->reminder_24h_sent ? 'text-green-600' : 'text-earth-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $order->reminder_24h_sent ? 'M5 13l4 4L19 7' : 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                    </svg>
                    Rappel 24h avant
                </div>
                <div class="flex items-center gap-2 text-sm {{ $order->reminder_1h_sent ? 'text-green-600' : 'text-earth-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $order->reminder_1h_sent ? 'M5 13l4 4L19 7' : 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                    </svg>
                    Rappel 1h avant
                </div>
            </div>
        </div>
    </div>

    {{-- Articles --}}
    <div class="card mt-5">
        <h3 class="font-serif font-semibold text-earth-700 mb-4">Articles commandés</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-earth-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">Produit</th>
                    <th class="px-4 py-2 text-right">Prix unit.</th>
                    <th class="px-4 py-2 text-center">Qté</th>
                    <th class="px-4 py-2 text-right">Sous-total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                <tr>
                    <td class="px-4 py-3 font-medium text-earth-800">{{ $item->product_name }}</td>
                    <td class="px-4 py-3 text-right text-earth-600">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                    <td class="px-4 py-3 text-center text-earth-600">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-earth-800">{{ number_format($item->subtotal, 2, ',', ' ') }} €</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t-2 border-earth-200">
                <tr>
                    <td colspan="3" class="px-4 py-3 font-bold text-earth-700 text-right">Total</td>
                    <td class="px-4 py-3 text-right font-bold text-xl text-earth-800">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($order->notes)
    <div class="card mt-5">
        <h3 class="font-serif font-semibold text-earth-700 mb-2">Notes du client</h3>
        <p class="text-earth-600 text-sm">{{ $order->notes }}</p>
    </div>
    @endif

    {{-- Changer statut --}}
    <div class="card mt-5">
        <h3 class="font-serif font-semibold text-earth-700 mb-4">Mettre à jour le statut</h3>
        <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="flex gap-3 items-end">
            @csrf
            <div class="flex-1">
                <label class="form-label text-xs">Nouveau statut</label>
                <select name="status" class="form-input">
                    @foreach($statusLabels as $val => $label)
                        <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
@endsection
