@extends('layouts.admin')
@section('title', 'Nouvelle catégorie')
@section('page-title', 'Nouvelle catégorie')

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
        @csrf
        @include('admin.categories._form')
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Créer</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection
