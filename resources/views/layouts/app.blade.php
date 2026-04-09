<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tisane Lontan') - Saveurs naturelles de la Réunion</title>
    <meta name="description" content="@yield('meta_description', 'Tisanes, miels, sirops et plantes artisanaux de la Réunion. Commandez en ligne, retirez sur place.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">

{{-- Navigation --}}
<header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="text-2xl">🌿</span>
                <div class="leading-tight">
                    <span class="font-serif text-xl font-bold text-herb-700 group-hover:text-herb-600 transition-colors">Tisane Lontan</span>
                    <span class="block text-xs text-earth-400 font-sans">Saveurs naturelles</span>
                </div>
            </a>

            {{-- Nav desktop --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                <a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}">Boutique</a>
                <a href="{{ route('shop.index', ['categorie' => 'tisanes']) }}" class="nav-link">Tisanes</a>
                <a href="{{ route('shop.index', ['categorie' => 'miels']) }}" class="nav-link">Miels</a>
            </div>

            {{-- Actions droite --}}
            <div class="flex items-center gap-3">
                {{-- Panier --}}
                <a href="{{ route('cart.index') }}" class="relative flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-herb-50 transition-colors text-earth-700 hover:text-herb-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="hidden sm:inline font-medium text-sm">Panier</span>
                    @php $cartCount = collect(session('cart', []))->sum('quantity') @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- Admin --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-outline btn-sm">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-earth-500 hover:text-earth-700 transition-colors">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm nav-link">Connexion</a>
                @endauth

                {{-- Menu mobile --}}
                <button id="menu-toggle" class="md:hidden p-2 rounded-lg hover:bg-herb-50 transition-colors">
                    <svg class="w-5 h-5 text-earth-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu mobile déroulant --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-cream-200 mt-2 pt-3 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 nav-link">Accueil</a>
            <a href="{{ route('shop.index') }}" class="block py-2 nav-link">Boutique</a>
            <a href="{{ route('shop.index', ['categorie' => 'tisanes']) }}" class="block py-2 nav-link">Tisanes</a>
            <a href="{{ route('shop.index', ['categorie' => 'miels']) }}" class="block py-2 nav-link">Miels</a>
            <a href="{{ route('shop.index', ['categorie' => 'sirops']) }}" class="block py-2 nav-link">Sirops</a>
            <a href="{{ route('shop.index', ['categorie' => 'plantes']) }}" class="block py-2 nav-link">Plantes</a>
        </div>
    </nav>
</header>

{{-- Flash messages --}}
@if(session('success'))
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-4">
        <div class="alert alert-success">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    </div>
@endif
@if(session('error'))
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-4">
        <div class="alert alert-error">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    </div>
@endif

{{-- Contenu --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-herb-900 text-herb-100 mt-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🌿</span>
                    <span class="font-serif text-xl font-bold text-white">Tisane Lontan</span>
                </div>
                <p class="text-herb-300 text-sm leading-relaxed">
                    Produits naturels et artisanaux de la Réunion. Tisanes, miels, sirops et plantes cultivés avec soin pour votre bien-être.
                </p>
            </div>
            <div>
                <h4 class="font-serif text-white font-semibold mb-4">Navigation</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-herb-300 hover:text-white transition-colors">Accueil</a></li>
                    <li><a href="{{ route('shop.index') }}" class="text-herb-300 hover:text-white transition-colors">Boutique</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-herb-300 hover:text-white transition-colors">Panier</a></li>
                    <li><a href="{{ route('checkout.index') }}" class="text-herb-300 hover:text-white transition-colors">Commander</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-serif text-white font-semibold mb-4">Nos produits</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('shop.index', ['categorie' => 'tisanes']) }}" class="text-herb-300 hover:text-white transition-colors">Tisanes</a></li>
                    <li><a href="{{ route('shop.index', ['categorie' => 'miels']) }}" class="text-herb-300 hover:text-white transition-colors">Miels</a></li>
                    <li><a href="{{ route('shop.index', ['categorie' => 'sirops']) }}" class="text-herb-300 hover:text-white transition-colors">Sirops</a></li>
                    <li><a href="{{ route('shop.index', ['categorie' => 'plantes']) }}" class="text-herb-300 hover:text-white transition-colors">Plantes</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-herb-700 mt-8 pt-6 text-center text-herb-400 text-sm">
            &copy; {{ date('Y') }} Tisane Lontan. Tous droits réservés. Produits artisanaux de La Réunion.
        </div>
    </div>
</footer>

<script>
    document.getElementById('menu-toggle')?.addEventListener('click', () => {
        document.getElementById('mobile-menu')?.classList.toggle('hidden');
    });
</script>
@stack('scripts')
</body>
</html>
