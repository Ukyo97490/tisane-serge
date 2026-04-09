@extends('layouts.app')

@section('title', 'Commander')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-10">
    <h1 class="section-title mb-8">Finaliser la commande</h1>

    @if($errors->any())
        <div class="alert alert-error mb-6">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="grid md:grid-cols-3 gap-8">

            {{-- Formulaire principal --}}
            <div class="md:col-span-2 space-y-6">

                {{-- Coordonnées --}}
                <div class="card">
                    <h2 class="font-serif text-lg font-semibold text-earth-800 mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 bg-herb-100 rounded-full text-herb-700 flex items-center justify-center text-sm font-bold">1</span>
                        Vos coordonnées
                    </h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label" for="customer_name">Nom complet <span class="text-red-500">*</span></label>
                            <input type="text" id="customer_name" name="customer_name"
                                   value="{{ old('customer_name') }}"
                                   class="form-input" placeholder="Jean Dupont" required>
                        </div>
                        <div>
                            <label class="form-label" for="customer_phone">Téléphone</label>
                            <input type="tel" id="customer_phone" name="customer_phone"
                                   value="{{ old('customer_phone') }}"
                                   class="form-input" placeholder="0692 xx xx xx">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label" for="customer_email">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="customer_email" name="customer_email"
                                   value="{{ old('customer_email') }}"
                                   class="form-input" placeholder="votre@email.com" required>
                            <p class="text-earth-400 text-xs mt-1">Les rappels de retrait vous seront envoyés à cette adresse.</p>
                        </div>
                    </div>
                </div>

                {{-- Point de retrait --}}
                <div class="card">
                    <h2 class="font-serif text-lg font-semibold text-earth-800 mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 bg-herb-100 rounded-full text-herb-700 flex items-center justify-center text-sm font-bold">2</span>
                        Point de retrait
                    </h2>

                    <div class="space-y-3 mb-5">
                        @foreach($pickupPoints as $point)
                        <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer transition-colors hover:border-herb-300 {{ old('pickup_point_id') == $point->id ? 'border-herb-500 bg-herb-50' : 'border-earth-100' }}"
                               id="label-point-{{ $point->id }}">
                            <input type="radio" name="pickup_point_id" value="{{ $point->id }}"
                                   {{ old('pickup_point_id') == $point->id ? 'checked' : '' }}
                                   class="mt-1 accent-herb-600 shrink-0" required
                                   onchange="onPointChange({{ $point->id }})">
                            <div class="flex-1">
                                <div class="font-semibold text-earth-800">{{ $point->name }}</div>
                                <div class="text-earth-500 text-sm">{{ $point->full_address }}</div>
                                @if($point->description)
                                    <div class="text-earth-400 text-xs mt-1">{{ $point->description }}</div>
                                @endif
                                <div class="mt-2 space-y-0.5">
                                    @foreach($point->activeSlots as $slot)
                                    <span class="inline-block text-xs bg-herb-100 text-herb-700 rounded-full px-2 py-0.5 mr-1">
                                        {{ $slot->day_name }} {{ $slot->formatted_hours }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Date et heure --}}
                    <div class="grid sm:grid-cols-2 gap-4 pt-4 border-t border-cream-200">
                        <div>
                            <label class="form-label" for="pickup_date">Date de retrait <span class="text-red-500">*</span></label>
                            <input type="date" id="pickup_date" name="pickup_date"
                                   value="{{ old('pickup_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="form-input" required
                                   onchange="loadSlots()">
                        </div>
                        <div>
                            <label class="form-label" for="pickup_time">Heure de retrait <span class="text-red-500">*</span></label>
                            <select id="pickup_time" name="pickup_time" class="form-input" required>
                                <option value="">Choisir une heure...</option>
                                @if(old('pickup_time'))
                                    <option value="{{ old('pickup_time') }}" selected>{{ old('pickup_time') }}</option>
                                @endif
                            </select>
                            <p id="slots-message" class="text-earth-400 text-xs mt-1 hidden">Aucun créneau disponible ce jour.</p>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card">
                    <h2 class="font-serif text-lg font-semibold text-earth-800 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 bg-herb-100 rounded-full text-herb-700 flex items-center justify-center text-sm font-bold">3</span>
                        Notes (optionnel)
                    </h2>
                    <textarea name="notes" rows="3" class="form-input"
                              placeholder="Instructions particulières, allergies, questions...">{{ old('notes') }}</textarea>
                </div>

            </div>

            {{-- Récapitulatif --}}
            <div>
                <div class="card sticky top-24">
                    <h2 class="font-serif text-lg font-semibold text-earth-800 mb-4">Votre commande</h2>
                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                        <div class="flex items-center justify-between gap-2 text-sm">
                            <div class="flex-1 min-w-0">
                                <span class="font-medium text-earth-700 truncate block">{{ $item['name'] }}</span>
                                <span class="text-earth-400">x{{ $item['quantity'] }}</span>
                            </div>
                            <span class="font-semibold text-earth-800 shrink-0">{{ number_format($item['price'] * $item['quantity'], 2, ',', ' ') }} €</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t border-cream-200 pt-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-semibold text-earth-700">Total</span>
                            <span class="font-bold text-xl text-earth-800">{{ number_format($total, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-3 text-xs text-amber-700 mb-5 flex items-start gap-1.5">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Paiement en espèces ou chèque sur place lors du retrait.
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center text-base py-3">
                            Valider la commande
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
let selectedPointId = null;

document.querySelectorAll('input[name="pickup_point_id"]').forEach(radio => {
    radio.addEventListener('change', () => {
        selectedPointId = radio.value;
        document.querySelectorAll('[id^="label-point-"]').forEach(l => {
            l.classList.remove('border-herb-500', 'bg-herb-50');
            l.classList.add('border-earth-100');
        });
        document.getElementById('label-point-' + selectedPointId)?.classList.remove('border-earth-100');
        document.getElementById('label-point-' + selectedPointId)?.classList.add('border-herb-500', 'bg-herb-50');
        loadSlots();
    });
});

// Init si déjà sélectionné
const checkedRadio = document.querySelector('input[name="pickup_point_id"]:checked');
if (checkedRadio) selectedPointId = checkedRadio.value;

function onPointChange(id) {
    selectedPointId = id;
    loadSlots();
}

function loadSlots() {
    const date = document.getElementById('pickup_date').value;
    const select = document.getElementById('pickup_time');
    const msg = document.getElementById('slots-message');

    if (!selectedPointId || !date) return;

    fetch(`{{ route('checkout.slots') }}?pickup_point_id=${selectedPointId}&date=${date}`)
        .then(r => r.json())
        .then(slots => {
            select.innerHTML = '<option value="">Choisir une heure...</option>';
            msg.classList.add('hidden');

            if (slots.length === 0) {
                msg.classList.remove('hidden');
                return;
            }

            // Générer des créneaux de 30 min dans la plage
            slots.forEach(slot => {
                const [oh, om] = slot.open_time.split(':').map(Number);
                const [ch, cm] = slot.close_time.split(':').map(Number);
                let cur = oh * 60 + om;
                const end = ch * 60 + cm;
                while (cur < end) {
                    const h = String(Math.floor(cur / 60)).padStart(2, '0');
                    const m = String(cur % 60).padStart(2, '0');
                    const opt = document.createElement('option');
                    opt.value = `${h}:${m}`;
                    opt.textContent = `${h}h${m}`;
                    select.appendChild(opt);
                    cur += 30;
                }
            });
        });
}
</script>
@endpush
@endsection
