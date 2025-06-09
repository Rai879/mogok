<?php

namespace App\Http\Controllers;

use App\Models\Compatible;
use App\Models\Part; // Import the Part model
use Illuminate\Http\Request;

class CompatibleController extends Controller
{
    public function index(Request $request)
    {
        $query = Compatible::with('part'); // Eager load the part relationship

        if ($request->filled('sort') && $request->filled('direction')) {
            if ($request->sort == 'part_name') {
                $query->join('parts', 'compatibles.part_id', '=', 'parts.id')
                      ->orderBy('parts.name', $request->direction)
                      ->select('compatibles.*'); // Select compatibles columns to avoid ambiguity
            } else {
                $query->orderBy($request->sort, $request->direction);
            }
        } else {
            $query->orderBy('vehicle_make', 'asc'); // Default sort
        }

        $compatibles = $query->paginate(10);
        $parts = Part::all(); // Get all parts for the autocomplete dropdown

        return view('compatibles.index', compact('compatibles', 'parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'engine_type' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
        ]);

        Compatible::create([
            'part_id' => $request->part_id,
            'vehicle_make' => $request->vehicle_make,
            'vehicle_model' => $request->vehicle_model,
            'vehicle_year' => $request->vehicle_year,
            'engine_type' => $request->engine_type,
            'is_verified' => $request->has('is_verified'),
        ]);

        return redirect()->route('compatibles.index')->with('success', 'Kecocokan berhasil ditambahkan!');
    }

    public function update(Request $request, Compatible $compatible)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'engine_type' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
        ]);

        $compatible->update([
            'part_id' => $request->part_id,
            'vehicle_make' => $request->vehicle_make,
            'vehicle_model' => $request->vehicle_model,
            'vehicle_year' => $request->vehicle_year,
            'engine_type' => $request->engine_type,
            'is_verified' => $request->has('is_verified'),
        ]);

        return redirect()->route('compatibles.index')->with('success', 'Kecocokan berhasil diperbarui!');
    }

    public function destroy(Compatible $compatible)
    {
        $compatible->delete();
        return redirect()->route('compatibles.index')->with('success', 'Kecocokan berhasil dihapus!');
    }
}