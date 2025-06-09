<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str for slug generation

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        // Simple backend search (optional, if you want full database search)
        // if ($request->filled('search')) {
        //     $search = $request->input('search');
        //     $query->where('name', 'like', '%' . $search . '%')
        //           ->orWhere('slug', 'like', '%' . $search . '%')
        //           ->orWhere('description', 'like', '%' . $search . '%');
        // }

        // Sorting logic (if you want to keep backend sorting)
        if ($request->filled('sort') && $request->filled('direction')) {
            $query->orderBy($request->sort, $request->direction);
        } else {
            $query->orderBy('name', 'asc'); // Default sort
        }

        $categories = $query->paginate(10); // Adjust pagination limit as needed

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            // Slug akan di-generate ulang di sini untuk memastikan keunikan dan kebenaran
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name); // Generate slug from name
        $category->description = $request->description;
        $category->is_active = $request->has('is_active'); // Check if checkbox is ticked
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        // This method is not strictly necessary if you handle edit through the index page form
        // But it's good practice to have it for a dedicated edit page if needed.
        // For this setup, the edit data is passed via data attributes on the button.
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name); // Re-generate slug on update
        $category->description = $request->description;
        $category->is_active = $request->has('is_active');
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}