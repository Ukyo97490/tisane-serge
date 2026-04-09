@extends('layouts.admin')
@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    @foreach([
        ['label' => 'Commandes aujourd\'hui', 'value' => $stats['orders_today'], 'color' => 'bg-blue-50 text-blue-700', 'icon' => '📦'],
        ['label' => 'En attente', 'value' => $stats['orders_pending'], 'color' => 'bg-amber-50 text-amber-700', 'icon' => '⏳'],
        ['label' => 'Confirmées', 'value' => $stats['orders_confirmed'], 'color' => 'bg-indigo-50 text-indigo-700', 'icon' => '✅'],
        ['label' => 'Prêtes', 'value' => $stats['orders_ready'], 'color' => 'bg-green-50 text-green-700', 'icon' => '🟢'],
        ['label' => 'CA du mois', 'value' => number_format($stats['revenue_month'], 2, ',', ' ') . ' €', 'color' => 'bg-herb-50 text-herb-700', 'icon' => '💶'],
        ['label' => 'Stock faible', 'value' => $stats['low_stock'], 'color' => 'bg-red-50 text-red-700', 'icon' => '⚠️'],
    ] as $stat)
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="text-2xl mb-1">{{ $stat['icon'] }}</div>
        <div class="font-bold text-xl text-earth-800">{{ $stat['value'] }}</div>
        <div class="text-xs text-earth-400 mt-0.5 leading-tight">{{ $stat['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Dernières commandes --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-serif font-semibold text-earth-800">Dernières commandes</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-herb-600 text-sm font-medium hover:underline">Voir toutes</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-earth-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Référence</th>
                    <th class="px-5 py-3 text-left">Client</th>
                    <th class="px-5 py-3 text-left">Point de retrait</th>
                    <th class="px-5 py-3 text-left">Date retrait</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3 text-center">Statut</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($recentOrders as $order)
                <tr class="table-row-hover">
                    <td class="px-5 py-3 font-mono font-medium text-herb-700">{{ $order->reference }}</td>
                    <td class="px-5 py-3">
                        <div class="font-medium text-earth-800">{{ $order->customer_name }}</div>
                        <div class="text-earth-400 text-xs">{{ $order->customer_email }}</div>
                    </td>
                    <td class="px-5 py-3 text-earth-600">{{ $order->pickupPoint->name }}</td>
                    <td class="px-5 py-3 text-earth-600">
                        {{ \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') }}
                        <span class="text-earth-400">{{ substr($order->pickup_time, 0, 5) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-earth-800">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                    <td class="px-5 py-3 text-center">
                        <span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-herb-600 hover:underline text-xs font-medium">Voir</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-earth-400">Aucune commande pour l'instant.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
