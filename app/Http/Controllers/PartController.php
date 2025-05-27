<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Category;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $query = Part::with('category'); // Eager loading relationship
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Sorting functionality
        $allowedSorts = ['name', 'price', 'stock_quantity', 'is_active', 'category_id', 'created_at'];
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
        if ($sort === 'category_id') {
            // Special handling for category sorting - join with categories table
            $query->leftJoin('categories', 'parts.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $direction)
                  ->select('parts.*'); // Select only parts columns to avoid conflicts
        } else {
            $query->orderBy($sort, $direction);
        }
        
        // Pagination - 10 items per page (you can change this)
        $parts = $query->paginate(10);
        
        return view('parts.index', compact('parts'));
    }
    public function create()
    {
        $categories = Category::all();
        return view('parts.create', compact('categories'));
    }

    public function store(Request $request)
    {
    $data = $request->validate([
        'name' => 'required',
        'part_number' => 'required|unique:parts',
        'description' => 'nullable',
        'category_id' => 'required|exists:categories,id',
        'brand' => 'nullable',
        'price' => 'required|numeric',
        'stock_quantity' => 'required|integer',
        'minimum_stock' => 'required|integer',
        'condition' => 'required|in:new,used,refurbished',
        'specifications' => 'nullable',
        'image' => 'nullable',
        'notes' => 'nullable',
        // 'is_active' tidak divalidasi di sini, kita tangani manual
    ]);

    // Atur nilai is_active secara eksplisit: 1 jika checkbox dicentang, 0 jika tidak
    $data['is_active'] = $request->has('is_active') ? 1 : 0;

    // Konversi spesifikasi menjadi JSON jika ada
    if ($request->filled('specifications')) {
        $data['specifications'] = json_encode($request->specifications);
    }

    Part::create($data);

    return redirect()->route('parts.index')->with('success', 'Sparepart berhasil ditambahkan.');
    }


    public function edit(Part $part)
    {
        $categories = Category::all();
        return view('parts.edit', compact('part', 'categories'));
    }

    public function update(Request $request, Part $part)
    {
        $data = $request->validate([
            'name' => 'required',
            'part_number' => 'required|unique:parts,part_number,' . $part->id,
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'minimum_stock' => 'required|integer',
            'condition' => 'required|in:new,used,refurbished',
            'specifications' => 'nullable',
            'image' => 'nullable',
            'notes' => 'nullable',
            'is_active' => 'boolean',
        ]);

        if ($request->specifications) {
            $data['specifications'] = json_encode($request->specifications);
        }

        $part->update($data);
        return redirect()->route('parts.index')->with('success', 'Sparepart berhasil diperbarui.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Sparepart berhasil dihapus.');
    }

    
}
