<?php

namespace App\Http\Controllers;

use App\Models\Compatible;
use App\Models\Part;
use Illuminate\Http\Request;

class CompatibleController extends Controller
{
    public function index()
    {
        $compatibles = Compatible::with('part')->get();
        return view('compatibles.index', compact('compatibles'));
    }

    public function create()
    {
        $parts = Part::all();
        return view('compatibles.create', compact('parts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'vehicle_make' => 'required',
            'vehicle_model' => 'required',
            'vehicle_year' => 'nullable',
            'engine_type' => 'nullable',
            'notes' => 'nullable',
            'is_verified' => 'boolean',
        ]);

        Compatible::create($data);
        return redirect()->route('compatibles.index')->with('success', 'Kecocokan berhasil ditambahkan.');
    }

    public function edit(Compatible $compatible)
    {
        $parts = Part::all();
        return view('compatibles.edit', compact('compatible', 'parts'));
    }

    public function update(Request $request, Compatible $compatible)
    {
        $data = $request->validate([
            'part_id' => 'required|exists:parts,id',
            'vehicle_make' => 'required',
            'vehicle_model' => 'required',
            'vehicle_year' => 'nullable',
            'engine_type' => 'nullable',
            'notes' => 'nullable',
            'is_verified' => 'boolean',
        ]);

        $compatible->update($data);
        return redirect()->route('compatibles.index')->with('success', 'Kecocokan berhasil diperbarui.');
    }

    public function destroy(Compatible $compatible)
    {
        $compatible->delete();
        return redirect()->route('compatibles.index')->with('success', 'Kecocokan berhasil dihapus.');
    }
}
