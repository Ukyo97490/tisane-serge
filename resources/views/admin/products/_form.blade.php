@if($errors->any())
<div class="alert alert-error">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <ul class="list-disc list-inside text-sm">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
</div>
@endif

<div class="card space-y-5">
    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2">
            <label class="form-label">Catégorie <span class="text-red-500">*</span></label>
            <select name="category_id" class="form-input" required>
                <option value="">Choisir...</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="form-label">Nom du produit <span class="text-red-500">*</span></label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div>
            <label class="form-label">Prix (€) <span class="text-red-500">*</span></label>
            <input type="number" name="price" step="0.01" min="0" class="form-input"
                   value="{{ old('price', $product->price ?? '') }}" required>
        </div>
        <div>
            <label class="form-label">Unité <span class="text-red-500">*</span></label>
            <input type="text" name="unit" class="form-input"
                   value="{{ old('unit', $product->unit ?? 'sachet') }}" placeholder="sachet, pot, bouteille..." required>
        </div>

        <div>
            <label class="form-label">Stock <span class="text-red-500">*</span></label>
            <input type="number" name="stock" min="0" class="form-input"
                   value="{{ old('stock', $product->stock ?? 0) }}" required>
        </div>
        <div>
            <label class="form-label">Ordre d'affichage</label>
            <input type="number" name="sort_order" min="0" class="form-input"
                   value="{{ old('sort_order', $product->sort_order ?? 0) }}">
        </div>
    </div>

    <div>
        <label class="form-label">Description</label>
        <textarea name="description" rows="3" class="form-input">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="form-label">Bienfaits</label>
        <textarea name="benefits" rows="3" class="form-input" placeholder="Propriétés, bienfaits pour la santé...">{{ old('benefits', $product->benefits ?? '') }}</textarea>
    </div>

    <div>
        <label class="form-label">Image du produit</label>
        @if(isset($product) && $product->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $product->image) }}" class="h-24 w-24 object-cover rounded-lg border border-earth-200">
                <p class="text-xs text-earth-400 mt-1">Image actuelle. Sélectionnez une nouvelle image pour la remplacer.</p>
            </div>
        @endif
        <input type="file" name="image" accept="image/*" class="form-input py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:bg-herb-100 file:text-herb-700 file:text-sm file:font-medium">
        <p class="text-earth-400 text-xs mt-1">JPEG, PNG ou WebP, max 2 Mo.</p>
    </div>

    <div class="flex items-center gap-3">
        <input type="checkbox" name="active" id="active" value="1"
               {{ old('active', $product->active ?? true) ? 'checked' : '' }}
               class="rounded accent-herb-600">
        <label for="active" class="font-medium text-earth-700">Produit visible en boutique</label>
    </div>
</div>
