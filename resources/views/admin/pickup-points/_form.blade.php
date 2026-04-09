@if($errors->any())
<div class="alert alert-error">
    <ul class="list-disc list-inside text-sm">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
</div>
@endif

<div class="card space-y-4">
    <h3 class="font-serif font-semibold text-earth-700">Informations</h3>
    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <label class="form-label">Nom du stand <span class="text-red-500">*</span></label>
            <input type="text" name="name" class="form-input"
                   value="{{ old('name', $point->name ?? '') }}" required
                   placeholder="Marché de Saint-Denis">
        </div>
        <div class="sm:col-span-2">
            <label class="form-label">Adresse <span class="text-red-500">*</span></label>
            <input type="text" name="address" class="form-input"
                   value="{{ old('address', $point->address ?? '') }}" required
                   placeholder="12 rue des Lilas">
        </div>
        <div>
            <label class="form-label">Ville <span class="text-red-500">*</span></label>
            <input type="text" name="city" class="form-input"
                   value="{{ old('city', $point->city ?? '') }}" required
                   placeholder="Saint-Denis">
        </div>
        <div>
            <label class="form-label">Code postal</label>
            <input type="text" name="postal_code" class="form-input"
                   value="{{ old('postal_code', $point->postal_code ?? '') }}"
                   placeholder="97400">
        </div>
        <div>
            <label class="form-label">Téléphone contact</label>
            <input type="text" name="contact_phone" class="form-input"
                   value="{{ old('contact_phone', $point->contact_phone ?? '') }}"
                   placeholder="0692 xx xx xx">
        </div>
        <div class="flex items-end pb-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="active" value="1"
                       {{ old('active', $point->active ?? true) ? 'checked' : '' }}
                       class="accent-herb-600 rounded">
                <span class="font-medium text-earth-700">Point actif</span>
            </label>
        </div>
        <div class="sm:col-span-2">
            <label class="form-label">Description</label>
            <textarea name="description" rows="2" class="form-input"
                      placeholder="Informations complémentaires sur ce stand...">{{ old('description', $point->description ?? '') }}</textarea>
        </div>
    </div>
</div>

{{-- Créneaux horaires --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-serif font-semibold text-earth-700">Créneaux horaires</h3>
        <button type="button" onclick="addSlot()" class="btn-outline btn-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajouter un créneau
        </button>
    </div>
    <p class="text-earth-400 text-xs mb-4">Définissez les jours et horaires d'ouverture de ce stand.</p>

    <div id="slots-container" class="space-y-2">
        @if(isset($point))
            @foreach($point->slots as $i => $slot)
            <div class="flex flex-wrap items-center gap-3 p-3 bg-cream-50 rounded-lg">
                <select name="slots[{{ $i }}][day_of_week]" class="form-input py-2 text-sm w-36">
                    @foreach([1=>'Lundi',2=>'Mardi',3=>'Mercredi',4=>'Jeudi',5=>'Vendredi',6=>'Samedi',0=>'Dimanche'] as $v => $l)
                        <option value="{{ $v }}" {{ $slot->day_of_week == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
                <input type="time" name="slots[{{ $i }}][open_time]" class="form-input py-2 text-sm w-28"
                       value="{{ substr($slot->open_time, 0, 5) }}">
                <span class="text-earth-400 text-sm">à</span>
                <input type="time" name="slots[{{ $i }}][close_time]" class="form-input py-2 text-sm w-28"
                       value="{{ substr($slot->close_time, 0, 5) }}">
                <label class="flex items-center gap-1 text-sm text-earth-600 shrink-0">
                    <input type="checkbox" name="slots[{{ $i }}][active]" value="1"
                           {{ $slot->active ? 'checked' : '' }} class="accent-herb-600"> Actif
                </label>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endforeach
        @endif
    </div>
    @if(!isset($point) || $point->slots->isEmpty())
        <p class="text-earth-400 text-sm text-center py-4" id="no-slots-msg">Aucun créneau. Cliquez "Ajouter un créneau".</p>
    @endif
</div>
