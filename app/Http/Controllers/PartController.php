<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Category;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::with('category')->get();
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
            'is_active' => 'boolean',
        ]);

        if ($request->specifications) {
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
