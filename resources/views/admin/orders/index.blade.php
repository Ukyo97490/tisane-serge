@extends('layouts.admin')
@section('title', $showArchived ? 'Commandes archivées' : 'Commandes')
@section('page-title', $showArchived ? 'Commandes archivées' : 'Commandes')

@section('content')

{{-- Onglets --}}
<div class="flex gap-2 mb-5">
    <a href="{{ route('admin.orders.index') }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !$showArchived ? 'bg-herb-700 text-white' : 'bg-white text-earth-600 hover:bg-gray-50 shadow-sm' }}">
        Commandes actives
    </a>
    <a href="{{ route('admin.orders.index', ['archives' => 1]) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $showArchived ? 'bg-herb-700 text-white' : 'bg-white text-earth-600 hover:bg-gray-50 shadow-sm' }}">
        Archives
    </a>
</div>

{{-- Filtres --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        @if($showArchived)
            <input type="hidden" name="archives" value="1">
        @endif
        <div class="flex-1 min-w-48">
            <label class="form-label text-xs">Recherche</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input py-2 text-sm"
                   placeholder="Référence, nom, email...">
        </div>
        <div>
            <label class="form-label text-xs">Statut</label>
            <select name="status" class="form-input py-2 text-sm">
                <option value="">Tous</option>
                @foreach($statusLabels as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label text-xs">Date retrait</label>
            <input type="date" name="date" value="{{ request('date') }}" class="form-input py-2 text-sm">
        </div>
        <button type="submit" class="btn-primary btn-sm py-2">Filtrer</button>
        @if(request()->hasAny(['q', 'status', 'date']))
            <a href="{{ route('admin.orders.index', $showArchived ? ['archives' => 1] : []) }}" class="btn-outline btn-sm py-2">Réinitialiser</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-earth-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3 text-left">Référence</th>
                    <th class="px-5 py-3 text-left">Client</th>
                    <th class="px-5 py-3 text-left">Point</th>
                    <th class="px-5 py-3 text-left">Retrait</th>
                    <th class="px-5 py-3 text-right">Total</th>
                    <th class="px-5 py-3 text-center">Statut</th>
                    <th class="px-5 py-3 text-center">Rappels</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="table-row-hover">
                    <td class="px-5 py-3 font-mono font-medium text-herb-700 text-xs">{{ $order->reference }}</td>
                    <td class="px-5 py-3">
                        <div class="font-medium text-earth-800">{{ $order->customer_name }}</div>
                        <div class="text-earth-400 text-xs">{{ $order->customer_email }}</div>
                    </td>
                    <td class="px-5 py-3 text-earth-600 text-xs">{{ $order->pickupPoint->name }}</td>
                    <td class="px-5 py-3 text-earth-600 text-xs">
                        {{ \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') }}
                        <br><span class="text-earth-400">{{ substr($order->pickup_time, 0, 5) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-earth-800">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                    <td class="px-5 py-3 text-center">
                        <span class="badge badge-{{ $order->status }}">{{ $order->status_label }}</span>
                    </td>
                    <td class="px-5 py-3 text-center text-xs text-earth-400">
                        <div class="flex items-center justify-center gap-1">
                            <span title="24h" class="{{ $order->reminder_24h_sent ? 'text-green-500' : 'text-gray-300' }}">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 13.24a.75.75 0 101.4.52l2-5.25a.75.75 0 000-.51z"/></svg>
                            </span>
                            <span title="1h" class="{{ $order->reminder_1h_sent ? 'text-green-500' : 'text-gray-300' }}">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 13.24a.75.75 0 101.4.52l2-5.25a.75.75 0 000-.51z"/></svg>
                            </span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline btn-sm py-1">Voir</a>
                            @if($showArchived)
                                <form method="POST" action="{{ route('admin.orders.unarchive', $order) }}">
                                    @csrf
                                    <button type="submit" class="btn-sm py-1 px-2 bg-gray-100 text-earth-600 hover:bg-gray-200 rounded-lg text-xs transition-colors">Désarchiver</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.orders.archive', $order) }}"
                                      onsubmit="return confirm('Archiver la commande {{ $order->reference }} ?')">
                                    @csrf
                                    <button type="submit" class="btn-sm py-1 px-2 bg-gray-100 text-earth-500 hover:bg-amber-50 hover:text-amber-700 rounded-lg text-xs transition-colors">Archiver</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-earth-400">Aucune commande.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $orders->withQueryString()->links() }}
    </div>
</div>
@endsection
