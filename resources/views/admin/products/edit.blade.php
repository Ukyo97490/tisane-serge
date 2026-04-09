@extends('layouts.admin')
@section('title', 'Modifier ' . $product->name)
@section('page-title', 'Modifier : ' . $product->name)

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.produits.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        @include('admin.products._form')
        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Enregistrer</button>
            <a href="{{ route('admin.produits.index') }}" class="btn-outline">Annuler</a>
        </div>
    </form>
</div>
@endsection
