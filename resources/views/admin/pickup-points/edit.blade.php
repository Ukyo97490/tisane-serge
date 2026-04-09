@extends('layouts.admin')
@section('title', 'Modifier ' . $pickupPoint->name)
@section('page-title', 'Modifier : ' . $pickupPoint->name)

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.pickup-points.update', $pickupPoint) }}" class="space-y-6">
        @csrf @method('PUT')
        @include('admin.pickup-points._form', ['point' => $pickupPoint])
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Enregistrer</button>
            <a href="{{ route('admin.pickup-points.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
let slotIndex = {{ $pickupPoint->slots->count() }};
const dayNames = {1:'Lundi',2:'Mardi',3:'Mercredi',4:'Jeudi',5:'Vendredi',6:'Samedi',0:'Dimanche'};

function addSlot() {
    const container = document.getElementById('slots-container');
    const div = document.createElement('div');
    div.className = 'flex flex-wrap items-center gap-3 p-3 bg-cream-50 rounded-lg';
    div.innerHTML = `
        <select name="slots[${slotIndex}][day_of_week]" class="form-input py-2 text-sm w-36">
            ${Object.entries(dayNames).map(([v,l]) => `<option value="${v}">${l}</option>`).join('')}
        </select>
        <input type="time" name="slots[${slotIndex}][open_time]" class="form-input py-2 text-sm w-28" value="08:00">
        <span class="text-earth-400 text-sm">à</span>
        <input type="time" name="slots[${slotIndex}][close_time]" class="form-input py-2 text-sm w-28" value="18:00">
        <label class="flex items-center gap-1 text-sm text-earth-600 shrink-0">
            <input type="checkbox" name="slots[${slotIndex}][active]" value="1" checked class="accent-herb-600"> Actif
        </label>
        <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    container.appendChild(div);
    slotIndex++;
}
</script>
@endpush
@endsection
