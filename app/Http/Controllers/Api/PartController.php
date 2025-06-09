<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\PartBarcode;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $parts = Part::where('name', 'like', '%' . $query . '%')
                     ->orWhere('part_number', 'like', '%' . $query . '%')
                     ->select('id', 'name', 'price', 'stock_quantity')
                     ->limit(10)
                     ->get();
        return response()->json($parts);
    }

    public function getPartByBarcode($barcode)
    {
        $partBarcode = PartBarcode::where('barcode', $barcode)->first();
        if ($partBarcode) {
            $part = $partBarcode->part;
            return response()->json($part);
        }
        return response()->json(['message' => 'Part not found for this barcode.'], 404);
    }
}