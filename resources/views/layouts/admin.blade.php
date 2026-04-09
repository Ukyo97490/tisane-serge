<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration') - Tisane Lontan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex">

{{-- Sidebar --}}
<aside class="w-64 bg-herb-900 text-white flex-shrink-0 flex flex-col min-h-screen">
    <div class="px-6 py-5 border-b border-herb-700">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="text-xl">🌿</span>
            <div>
                <div class="font-serif font-bold text-white leading-tight">Tisane Lontan</div>
                <div class="text-herb-400 text-xs">Administration</div>
            </div>
        </a>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-herb-700 text-white' : 'text-herb-200 hover:bg-herb-800 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Tableau de bord
        </a>

        <div class="pt-3 pb-1 px-3 text-herb-500 text-xs font-semibold uppercase tracking-wider">Catalogue</div>

        <a href="{{ route('admin.produits.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.produits.*') ? 'bg-herb-700 text-white' : 'text-herb-200 hover:bg-herb-800 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Produits
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-herb-700 text-white' : 'text-herb-200 hover:bg-herb-800 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Catégories
        </a>

        <div class="pt-3 pb-1 px-3 text-herb-500 text-xs font-semibold uppercase tracking-wider">Ventes</div>

        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-herb-700 text-white' : 'text-herb-200 hover:bg-herb-800 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Commandes
            @php $pending = \App\Models\Order::where('status','en_attente')->count() @endphp
            @if($pending > 0)
                <span class="ml-auto bg-amber-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $pending }}</span>
            @endif
        </a>

        <a href="{{ route('admin.pickup-points.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.pickup-points.*') ? 'bg-herb-700 text-white' : 'text-herb-200 hover:bg-herb-800 hover:text-white' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Points de retrait
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-herb-700">
        <div class="text-xs text-herb-400 mb-2">{{ auth()->user()->name }}</div>
        <div class="flex gap-2">
            <a href="{{ route('home') }}" class="text-xs text-herb-300 hover:text-white transition-colors">Voir le site</a>
            <span class="text-herb-600">·</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs text-herb-300 hover:text-white transition-colors">Déconnexion</button>
            </form>
        </div>
    </div>
</aside>

{{-- Contenu principal --}}
<div class="flex-1 flex flex-col min-h-screen overflow-hidden">
    <header class="bg-white border-b border-gray-200 px-6 py-4">
        <h1 class="font-serif text-xl font-semibold text-earth-800">@yield('page-title', 'Administration')</h1>
    </header>

    <main class="flex-1 px-6 py-6 overflow-auto">
        @if(session('success'))
            <div class="alert alert-success mb-5">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error mb-5">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
