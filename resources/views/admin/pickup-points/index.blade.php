@extends('layouts.admin')
@section('title', 'Points de retrait')
@section('page-title', 'Points de retrait')

@section('content')
<div class="flex justify-end mb-5">
    <a href="{{ route('admin.pickup-points.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouveau point de retrait
    </a>
</div>

<div class="grid md:grid-cols-3 gap-5">
    @forelse($points as $point)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-herb-700 text-white px-5 py-4">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <h3 class="font-serif font-semibold text-lg">{{ $point->name }}</h3>
                    <p class="text-herb-300 text-sm">{{ $point->full_address }}</p>
                </div>
                <span class="{{ $point->active ? 'badge bg-herb-500 text-white' : 'badge bg-herb-900 text-herb-400' }}">
                    {{ $point->active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
        </div>
        <div class="px-5 py-4">
            @if($point->description)
                <p class="text-earth-500 text-sm mb-3">{{ $point->description }}</p>
            @endif
            @if($point->contact_phone)
                <p class="text-earth-500 text-sm mb-3 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $point->contact_phone }}
                </p>
            @endif
            <div class="mb-4">
                <p class="text-xs font-semibold text-earth-400 uppercase mb-2">Créneaux ({{ $point->slots_count }})</p>
                @forelse($point->slots as $slot)
                    <span class="inline-block text-xs bg-herb-100 text-herb-700 rounded px-2 py-0.5 mr-1 mb-1">
                        {{ $slot->day_name }} {{ $slot->formatted_hours }}
                    </span>
                @empty
                    <span class="text-xs text-earth-400">Aucun créneau défini</span>
                @endforelse
            </div>
            <div class="flex gap-2 pt-3 border-t border-cream-200">
                <a href="{{ route('admin.pickup-points.edit', $point) }}" class="btn-outline btn-sm flex-1 justify-center">Modifier</a>
                <form method="POST" action="{{ route('admin.pickup-points.destroy', $point) }}"
                      onsubmit="return confirm('Supprimer ce point de retrait ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="md:col-span-3 text-center py-16 text-earth-400 bg-white rounded-xl">
        <div class="text-4xl mb-3">📍</div>
        <p class="font-serif text-lg">Aucun point de retrait</p>
        <a href="{{ route('admin.pickup-points.create') }}" class="btn-primary mt-4">Créer le premier</a>
    </div>
    @endforelse
</div>
@endsection
