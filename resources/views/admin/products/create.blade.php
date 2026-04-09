@extends('layouts.admin')
@section('title', 'Nouveau produit')
@section('page-title', 'Nouveau produit')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.produits.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @include('admin.products._form')
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Créer le produit</button>
            <a href="{{ route('admin.produits.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection
