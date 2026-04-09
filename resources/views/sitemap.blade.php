<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    {{-- Accueil --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Boutique (toutes catégories) --}}
    <url>
        <loc>{{ route('shop.index') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Pages catégories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('shop.index', ['categorie' => $category->slug]) }}</loc>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Pages produits --}}
    @foreach($products as $product)
    <url>
        <loc>{{ route('shop.show', $product->slug) }}</loc>
        <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
        @if($product->image)
        <image:image>
            <image:loc>{{ asset('storage/' . $product->image) }}</image:loc>
            <image:title>{{ $product->name }} — {{ $product->category->name }} | Tisane Lontan</image:title>
            <image:caption>{{ \Illuminate\Support\Str::limit($product->description, 100) }}</image:caption>
        </image:image>
        @endif
    </url>
    @endforeach

</urlset>
