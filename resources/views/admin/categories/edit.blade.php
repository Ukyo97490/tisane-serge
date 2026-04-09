@extends('layouts.admin')
@section('title', 'Modifier ' . $category->name)
@section('page-title', 'Modifier : ' . $category->name)

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
        @csrf @method('PUT')
        @include('admin.categories._form')
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Enregistrer</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection
