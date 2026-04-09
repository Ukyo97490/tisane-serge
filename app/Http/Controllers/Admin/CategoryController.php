<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'sort_order'  => 'integer|min:0',
            'active'      => 'boolean',
        ]);

        $data['slug']   = Str::slug($data['name']);
        $data['active'] = $request->boolean('active', true);

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:50',
            'sort_order'  => 'integer|min:0',
            'active'      => 'boolean',
        ]);

        $data['active'] = $request->boolean('active');
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer|exists:categories,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $query = Category::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $query->update(['active' => true]);
                return back()->with('success', count($request->ids) . ' catégorie(s) activée(s).');

            case 'deactivate':
                $query->update(['active' => false]);
                return back()->with('success', count($request->ids) . ' catégorie(s) désactivée(s).');

            case 'delete':
                $query->delete();
                return back()->with('success', count($request->ids) . ' catégorie(s) supprimée(s).');
        }
    }
}
