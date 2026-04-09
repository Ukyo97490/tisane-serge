@extends('layouts.admin')
@section('title', 'Produits')
@section('page-title', 'Produits')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-earth-500 text-sm">{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }}</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.produits.export') }}"
           class="btn-outline btn-sm flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Exporter ZIP
        </a>
        <button type="button" onclick="document.getElementById('import-panel').classList.toggle('hidden')"
                class="btn-outline btn-sm flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l4-4m0 0l4 4m-4-4v12"/></svg>
            Importer ZIP
        </button>
        <a href="{{ route('admin.produits.create') }}" class="btn-primary btn-sm flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau produit
        </a>
    </div>
</div>

{{-- Panneau import --}}
<div id="import-panel" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-5 mb-5">
    <h3 class="font-semibold text-earth-800 mb-1">Importer des produits</h3>
    <div class="flex items-start justify-between gap-4 mb-4">
        <p class="text-earth-500 text-xs">
            Le ZIP doit contenir un fichier <code class="bg-white px-1 rounded">produits.csv</code> (séparateur <code class="bg-white px-1 rounded">;</code>) et un dossier <code class="bg-white px-1 rounded">images/</code> optionnel.<br>
            Colonnes attendues : <code class="bg-white px-1 rounded">name, slug, category_slug, description, benefits, price, unit, stock, active, sort_order, image_filename</code><br>
            Les produits existants (même slug) sont mis à jour, les nouveaux sont créés.
        </p>
        <a href="{{ route('admin.produits.example') }}"
           class="shrink-0 flex items-center gap-1.5 px-3 py-2 bg-white border border-amber-300 text-amber-700 hover:bg-amber-50 rounded-lg text-xs font-medium transition-colors whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Télécharger le fichier exemple
        </a>
    </div>
    <form method="POST" action="{{ route('admin.produits.import') }}" enctype="multipart/form-data"
          class="flex items-end gap-3">
        @csrf
        <div class="flex-1">
            <label class="form-label text-xs">Fichier ZIP (max 50 Mo)</label>
            <input type="file" name="zip_file" accept=".zip" required
                   class="form-input py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:bg-herb-100 file:text-herb-700 file:cursor-pointer">
        </div>
        <button type="submit" class="btn-primary btn-sm py-2">Importer</button>
        <button type="button" onclick="document.getElementById('import-panel').classList.add('hidden')"
                class="btn-outline btn-sm py-2">Annuler</button>
    </form>
</div>

{{-- Filtres --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-40">
            <label class="form-label text-xs">Recherche</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-input py-2 text-sm" placeholder="Nom du produit...">
        </div>
        <div class="min-w-40">
            <label class="form-label text-xs">Catégorie</label>
            <select name="categorie" class="form-input py-2 text-sm">
                <option value="">Toutes</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary btn-sm py-2">Filtrer</button>
        @if(request()->hasAny(['q', 'categorie']))
            <a href="{{ route('admin.produits.index') }}" class="btn-outline btn-sm py-2">Réinitialiser</a>
        @endif
    </form>
</div>

{{-- Formulaire bulk --}}
<form id="bulk-form" method="POST" action="{{ route('admin.produits.bulk') }}">
    @csrf
    <input type="hidden" name="action" id="bulk-action">

    {{-- Barre d'actions lot (cachée par défaut) --}}
    <div id="bulk-bar" class="hidden items-center gap-3 bg-herb-50 border border-herb-200 rounded-xl px-4 py-3 mb-3">
        <span class="text-herb-700 text-sm font-medium">
            <span id="bulk-count">0</span> sélectionné(s)
        </span>
        <div class="flex items-center gap-2 ml-auto">
            <button type="button" onclick="submitBulk('activate')"
                    class="px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg text-xs font-medium transition-colors">
                Activer
            </button>
            <button type="button" onclick="submitBulk('deactivate')"
                    class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg text-xs font-medium transition-colors">
                Désactiver
            </button>
            <button type="button" onclick="submitBulk('delete')"
                    class="px-3 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg text-xs font-medium transition-colors">
                Supprimer
            </button>
            <button type="button" onclick="deselectAll()"
                    class="px-3 py-1.5 text-earth-400 hover:text-earth-600 rounded-lg text-xs transition-colors">
                Annuler
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-earth-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" id="check-all"
                                   class="rounded border-gray-300 text-herb-600 focus:ring-herb-500 cursor-pointer"
                                   onchange="toggleAll(this)">
                        </th>
                        <th class="px-5 py-3 text-left">Produit</th>
                        <th class="px-5 py-3 text-left">Catégorie</th>
                        <th class="px-5 py-3 text-right">Prix</th>
                        <th class="px-5 py-3 text-right">Stock</th>
                        <th class="px-5 py-3 text-center">Statut</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                    <tr class="table-row-hover" id="row-{{ $product->id }}">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="ids[]" value="{{ $product->id }}"
                                   class="row-check rounded border-gray-300 text-herb-600 focus:ring-herb-500 cursor-pointer"
                                   onchange="updateBulkBar()">
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-cream-100 rounded-lg overflow-hidden shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-lg">{{ $product->category->icon ?? '🌿' }}</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-earth-800">{{ $product->name }}</div>
                                    <div class="text-earth-400 text-xs">{{ $product->unit }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-earth-600">{{ $product->category->name }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-earth-800">{{ number_format($product->price, 2, ',', ' ') }} €</td>
                        <td class="px-5 py-3 text-right {{ $product->stock <= 5 ? 'text-red-600 font-semibold' : 'text-earth-600' }}">{{ $product->stock }}</td>
                        <td class="px-5 py-3 text-center">
                            <span class="{{ $product->active ? 'badge bg-green-100 text-green-700' : 'badge bg-gray-100 text-gray-500' }}">
                                {{ $product->active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.produits.edit', $product) }}" class="btn-outline btn-sm py-1">Modifier</a>
                                <form method="POST" action="{{ route('admin.produits.destroy', $product) }}"
                                      onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm py-1">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-earth-400">Aucun produit.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
</form>

@push('scripts')
<script>
function toggleAll(source) {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = source.checked);
    updateBulkBar();
}

function deselectAll() {
    document.querySelectorAll('.row-check, #check-all').forEach(cb => cb.checked = false);
    updateBulkBar();
}

function updateBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked');
    const bar = document.getElementById('bulk-bar');
    document.getElementById('bulk-count').textContent = checked.length;
    if (checked.length > 0) {
        bar.classList.remove('hidden');
        bar.classList.add('flex');
    } else {
        bar.classList.add('hidden');
        bar.classList.remove('flex');
    }
    const all = document.querySelectorAll('.row-check');
    document.getElementById('check-all').indeterminate = checked.length > 0 && checked.length < all.length;
    document.getElementById('check-all').checked = checked.length === all.length && all.length > 0;
}

function submitBulk(action) {
    const checked = document.querySelectorAll('.row-check:checked');
    if (!checked.length) return;
    if (action === 'delete' && !confirm(checked.length + ' produit(s) seront supprimés définitivement. Continuer ?')) return;
    document.getElementById('bulk-action').value = action;
    document.getElementById('bulk-form').submit();
}
</script>
@endpush
@endsection
