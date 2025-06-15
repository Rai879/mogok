<?php

namespace App\Http\Controllers;

use App\Models\PartBarcode;
use App\Models\Part;
use Illuminate\Http\Request;

class PartBarcodeController extends Controller
{
    public function index()
    {
        // Data PartBarcode diambil dengan paginasi 10 item per halaman
        $partBarcodes = PartBarcode::with('part')->paginate(10); 
        
        $parts = Part::all(); // Mengambil semua data Parts untuk fitur autocomplete
        return view('part_barcodes.index', compact('partBarcodes', 'parts'));
    }

    public function create()
    {
        $parts = Part::all();
        return view('part_barcodes.create', compact('parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'barcode' => 'required|string|unique:part_barcodes,barcode',
        ]);

        PartBarcode::create($request->all());

        return redirect()->route('part-barcodes.index')->with('success', 'Part barcode created successfully.');
    }

    public function edit(PartBarcode $partBarcode)
    {
        $parts = Part::all();
        return view('part_barcodes.edit', compact('partBarcode', 'parts'));
    }

    public function update(Request $request, PartBarcode $partBarcode)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'barcode' => 'required|string|unique:part_barcodes,barcode,' . $partBarcode->id,
        ]);

        $partBarcode->update($request->all());

        return redirect()->route('part-barcodes.index')->with('success', 'Part barcode updated successfully.');
    }

    public function destroy(PartBarcode $partBarcode)
    {
        $partBarcode->delete();
        return redirect()->route('part-barcodes.index')->with('success', 'Part barcode deleted successfully.');
    }
}
