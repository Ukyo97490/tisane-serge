@extends('layouts.app')

@section('seo_title', 'Connexion — Tisane Lontan')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <span class="text-4xl">🌿</span>
            <h1 class="font-serif text-2xl font-bold text-earth-800 mt-3">Connexion</h1>
            <p class="text-earth-500 text-sm mt-1">Espace administration Tisane Lontan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-8">
            @if($errors->any())
                <div class="alert alert-error mb-5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-input" placeholder="admin@tisane-lontan.re" required autofocus>
                </div>
                <div>
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded accent-herb-600">
                    <label for="remember" class="text-sm text-earth-600">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-3">Se connecter</button>
            </form>
        </div>

        <p class="text-center mt-4">
            <a href="{{ route('home') }}" class="text-sm text-earth-400 hover:text-herb-600 transition-colors">
                Retour à la boutique
            </a>
        </p>
    </div>
</div>
@endsection
