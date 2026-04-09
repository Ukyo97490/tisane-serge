@if($errors->any())
<div class="alert alert-error">
    <ul class="list-disc list-inside text-sm">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
</div>
@endif

<div class="card space-y-4">
    <div>
        <label class="form-label">Nom <span class="text-red-500">*</span></label>
        <input type="text" name="name" class="form-input"
               value="{{ old('name', $category->name ?? '') }}" required
               placeholder="Tisanes, Miels, Sirops...">
    </div>
    <div>
        <label class="form-label">Icone (emoji)</label>
        <input type="text" name="icon" class="form-input text-2xl"
               value="{{ old('icon', $category->icon ?? '') }}"
               placeholder="🌿" maxlength="5">
        <p class="text-earth-400 text-xs mt-1">Un emoji qui représente cette catégorie.</p>
    </div>
    <div>
        <label class="form-label">Description</label>
        <textarea name="description" rows="2" class="form-input"
                  placeholder="Description courte de la catégorie...">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Ordre d'affichage</label>
            <input type="number" name="sort_order" min="0" class="form-input"
                   value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        </div>
        <div class="flex items-end pb-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="active" value="1"
                       {{ old('active', $category->active ?? true) ? 'checked' : '' }}
                       class="rounded accent-herb-600">
                <span class="font-medium text-earth-700">Catégorie active</span>
            </label>
        </div>
    </div>
</div>
