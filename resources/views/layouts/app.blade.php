<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Titre --}}
    <title>@yield('seo_title', config('app.name'))</title>

    {{-- Meta description --}}
    <meta name="description" content="@yield('seo_description', 'Tisanes, miels, sirops et plantes artisanaux de La Réunion. Commandez en ligne et retirez sur place dans nos marchés.')">

    {{-- Robots --}}
    <meta name="robots" content="@yield('robots', 'index, follow')">

    {{-- Canonical --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Theme color --}}
    <meta name="theme-color" content="#2d6840">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="Tisane Lontan">
    <meta property="og:locale" content="fr_FR">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('seo_title', config('app.name'))">
    <meta property="og:description" content="@yield('seo_description', 'Tisanes, miels, sirops et plantes artisanaux de La Réunion.')">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.svg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('og_image_alt', 'Tisane Lontan — Saveurs naturelles de La Réunion')">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('seo_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('seo_description', 'Tisanes, miels, sirops et plantes artisanaux de La Réunion.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.svg'))">

    {{-- Pagination SEO --}}
    @yield('pagination_links')

    {{-- Polices --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- JSON-LD global WebSite + Organisation --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "@id": "{{ url('/') }}/#website",
          "url": "{{ url('/') }}",
          "name": "Tisane Lontan",
          "description": "Tisanes, miels, sirops et plantes artisanaux de La Réunion",
          "inLanguage": "fr-FR",
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "{{ route('shop.index') }}?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
          }
        },
        {
          "@type": "Organization",
          "@id": "{{ url('/') }}/#organization",
          "url": "{{ url('/') }}",
          "name": "Tisane Lontan",
          "description": "Produits naturels et artisanaux de La Réunion : tisanes, miels, sirops et plantes.",
          "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}",
            "width": 300,
            "height": 100
          },
          "sameAs": []
        }
      ]
    }
    </script>

    {{-- JSON-LD spécifique à la page --}}
    @yield('json_ld')
</head>
<body class="min-h-screen flex flex-col">

{{-- Navigation --}}
<header class="bg-white shadow-sm sticky top-0 z-50" role="banner">
    <nav class="max-w-6xl mx-auto px-4 sm:px-6" aria-label="Navigation principale">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group" aria-label="Tisane Lontan — Retour à l'accueil">
                <span class="text-2xl" aria-hidden="true">🌿</span>
                <div class="leading-tight">
                    <span class="font-serif text-xl font-bold text-herb-700 group-hover:text-herb-600 transition-colors">Tisane Lontan</span>
                    <span class="block text-xs text-earth-400 font-sans">Saveurs naturelles</span>
                </div>
            </a>

            {{-- Nav desktop --}}
            <div class="hidden md:flex items-center gap-6" role="list">
                <a href="{{ route('home') }}" role="listitem" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="{{ request()->routeIs('home') ? 'page' : 'false' }}">Accueil</a>
                <a href="{{ route('shop.index') }}" role="listitem" class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}" aria-current="{{ request()->routeIs('shop.*') ? 'page' : 'false' }}">Boutique</a>
                <a href="{{ route('shop.index', ['categorie' => 'tisanes']) }}" role="listitem" class="nav-link">Tisanes</a>
                <a href="{{ route('shop.index', ['categorie' => 'miels']) }}" role="listitem" class="nav-link">Miels</a>
            </div>

            {{-- Actions droite --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('cart.index') }}" class="relative flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-herb-50 transition-colors text-earth-700 hover:text-herb-700" aria-label="Voir mon panier">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span class="hidden sm:inline font-medium text-sm">Panier</span>
                    @php $cartCount = collect(session('cart', []))->sum('quantity') @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center" aria-label="{{ $cartCount }} article(s) dans le panier">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-outline btn-sm">Administration</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-earth-500 hover:text-earth-700 transition-colors">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm nav-link">Connexion</a>
                @endauth

                <button id="menu-toggle" class="md:hidden p-2 rounded-lg hover:bg-herb-50 transition-colors" aria-expanded="false" aria-controls="mobile-menu" aria-label="Ouvrir le menu">
                    <svg class="w-5 h-5 text-earth-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-cream-200 mt-2 pt-3 space-y-2" role="navigation" aria-label="Navigation mobile">
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
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-4" role="alert" aria-live="polite">
        <div class="alert alert-success">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    </div>
@endif
@if(session('error'))
    <div class="max-w-6xl mx-auto px-4 sm:px-6 mt-4" role="alert" aria-live="assertive">
        <div class="alert alert-error">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    </div>
@endif

<main id="main-content">
    @yield('content')
</main>

<footer class="bg-herb-900 text-herb-100 mt-16" role="contentinfo">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl" aria-hidden="true">🌿</span>
                    <span class="font-serif text-xl font-bold text-white">Tisane Lontan</span>
                </div>
                <p class="text-herb-300 text-sm leading-relaxed">
                    Produits naturels et artisanaux de La Réunion. Tisanes, miels, sirops et plantes cultivés avec soin pour votre bien-être.
                </p>
            </div>
            <nav aria-label="Navigation secondaire">
                <h2 class="font-serif text-white font-semibold mb-4">Navigation</h2>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-herb-300 hover:text-white transition-colors">Accueil</a></li>
                    <li><a href="{{ route('shop.index') }}" class="text-herb-300 hover:text-white transition-colors">Boutique</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-herb-300 hover:text-white transition-colors">Panier</a></li>
                    <li><a href="{{ route('checkout.index') }}" class="text-herb-300 hover:text-white transition-colors">Commander</a></li>
                </ul>
            </nav>
            <nav aria-label="Navigation par catégorie">
                <h2 class="font-serif text-white font-semibold mb-4">Nos produits</h2>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('shop.index', ['categorie' => 'tisanes']) }}" class="text-herb-300 hover:text-white transition-colors">Tisanes artisanales</a></li>
                    <li><a href="{{ route('shop.index', ['categorie' => 'miels']) }}" class="text-herb-300 hover:text-white transition-colors">Miels naturels</a></li>
                    <li><a href="{{ route('shop.index', ['categorie' => 'sirops']) }}" class="text-herb-300 hover:text-white transition-colors">Sirops de plantes</a></li>
                    <li><a href="{{ route('shop.index', ['categorie' => 'plantes']) }}" class="text-herb-300 hover:text-white transition-colors">Plantes médicinales</a></li>
                </ul>
            </nav>
        </div>
        <div class="border-t border-herb-700 mt-8 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-herb-400 text-sm">
            <span>&copy; {{ date('Y') }} Tisane Lontan. Tous droits réservés.</span>
            <a href="{{ route('sitemap') }}" class="text-herb-500 hover:text-herb-300 transition-colors text-xs">Plan du site</a>
        </div>
    </div>
</footer>

<script>
    const btn = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    btn?.addEventListener('click', () => {
        const open = !menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        btn.setAttribute('aria-expanded', String(!open));
    });
</script>
@stack('scripts')
</body>
</html>
