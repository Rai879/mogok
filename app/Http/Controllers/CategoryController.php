<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Sorting functionality
        $allowedSorts = ['name', 'slug', 'description', 'is_active', 'created_at', 'updated_at'];
        $sort = $request->get('sort', 'name'); // default sort by name
        $direction = $request->get('direction', 'asc'); // default ascending
        
        // Validate sort column
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'name';
        }
        
        // Validate sort direction
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }
        
        // Apply sorting
        $query->orderBy($sort, $direction);

        $categories = $query->get();

        $categories = $query->paginate(10);

        return view('categories.index', compact('categories'));
    }
    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable',
            'image' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);

        Category::create($data);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'description' => 'nullable',
            'image' => 'nullable',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $category->update($data);
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
