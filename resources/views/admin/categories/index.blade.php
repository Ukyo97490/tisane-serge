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

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-earth-500 text-xs uppercase tracking-wider">
            <tr>
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
                <td colspan="6" class="px-5 py-10 text-center text-earth-400">Aucune catégorie.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
