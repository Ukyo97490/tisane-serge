@extends('layouts.admin')
@section('title', 'Catégories')
@section('page-title', 'Catégories')

@section('content')
<div class="flex justify-end mb-5">
    <a href="{{ route('admin.categories.create') }}" class="btn-primary btn-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouvelle catégorie
    </a>
</div>

{{-- Formulaire bulk --}}
<form id="bulk-form" method="POST" action="{{ route('admin.categories.bulk') }}">
    @csrf
    <input type="hidden" name="action" id="bulk-action">

    {{-- Barre d'actions lot --}}
    <div id="bulk-bar" class="hidden items-center gap-3 bg-herb-50 border border-herb-200 rounded-xl px-4 py-3 mb-3">
        <span class="text-herb-700 text-sm font-medium">
            <span id="bulk-count">0</span> sélectionnée(s)
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
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-earth-500 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 w-10">
                        <input type="checkbox" id="check-all"
                               class="rounded border-gray-300 text-herb-600 focus:ring-herb-500 cursor-pointer"
                               onchange="toggleAll(this)">
                    </th>
                    <th class="px-5 py-3 text-left">Nom</th>
                    <th class="px-5 py-3 text-left">Icone</th>
                    <th class="px-5 py-3 text-center">Produits</th>
                    <th class="px-5 py-3 text-center">Ordre</th>
                    <th class="px-5 py-3 text-center">Statut</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $category)
                <tr class="table-row-hover">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="ids[]" value="{{ $category->id }}"
                               class="row-check rounded border-gray-300 text-herb-600 focus:ring-herb-500 cursor-pointer"
                               onchange="updateBulkBar()">
                    </td>
                    <td class="px-5 py-3 font-medium text-earth-800">{{ $category->name }}</td>
                    <td class="px-5 py-3 text-xl">{{ $category->icon ?? '' }}</td>
                    <td class="px-5 py-3 text-center text-earth-600">{{ $category->products_count }}</td>
                    <td class="px-5 py-3 text-center text-earth-500">{{ $category->sort_order }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="{{ $category->active ? 'badge bg-green-100 text-green-700' : 'badge bg-gray-100 text-gray-500' }}">
                            {{ $category->active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-outline btn-sm py-1">Modifier</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm py-1">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-earth-400">Aucune catégorie.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
    if (action === 'delete' && !confirm(checked.length + ' catégorie(s) seront supprimées définitivement. Continuer ?')) return;
    document.getElementById('bulk-action').value = action;
    document.getElementById('bulk-form').submit();
}
</script>
@endpush
@endsection
