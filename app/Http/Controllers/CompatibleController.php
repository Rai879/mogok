<?php

namespace App\Http\Controllers;

use App\Models\Compatible;
use App\Models\Part;
use Illuminate\Http\Request;

class CompatibleController extends Controller
{
    public function index(Request $request)
    {
        $query = Compatible::with('part');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('part', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhere('vehicle_make', 'like', "%{$search}%")
            ->orWhere('vehicle_model', 'like', "%{$search}%")
            ->orWhere('vehicle_year', 'like', "%{$search}%")
            ->orWhere('engine_type', 'like', "%{$search}%");
        }

         // Handle sorting
        $sortField = $request->get('sort', 'id'); // Default sorting by id
        $sortDirection = $request->get('direction', 'desc'); // Default descending
        
        // Validasi sort field untuk keamanan
        $allowedSortFields = [
            'part_name',
            'vehicle_make', 
            'vehicle_model', 
            'vehicle_year', 
            'engine_type', 
            'is_verified',
            'created_at',
            'updated_at'
        ];
        
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'id';
        }
        
        // Validasi sort direction
        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
        
        // Apply sorting
        if ($sortField === 'part_name') {
            // Sorting berdasarkan nama part (relasi)
            $query->join('parts', 'compatibles.part_id', '=', 'parts.id')
                  ->select('compatibles.*')
                  ->orderBy('parts.name', $sortDirection);
        } else {
            // Sorting berdasarkan field langsung dari tabel compatibles
            $query->orderBy($sortField, $sortDirection);
        }

        $compatibles = $query->get();

        $compatibles = $query->paginate(10);

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
