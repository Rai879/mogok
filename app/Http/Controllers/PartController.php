<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // Import Storage Facade

class PartController extends Controller
{
    /**
     * Menampilkan daftar sparepart.
     * Dapat menangani pencarian, pengurutan, dan paginasi.
     * Mengembalikan partial view untuk request AJAX.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Part::with('category');

        // Logika pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('part_number', 'like', '%' . $search . '%');
        }

        // Logika pengurutan
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        // Paginasi hasil
        $parts = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        // Jika request adalah AJAX, kembalikan hanya partial view untuk baris tabel
        if ($request->ajax()) {
            return view('partials.parts_table_rows', compact('parts'))->render();
        }

        // Jika bukan AJAX, kembalikan halaman lengkap
        return view('parts.index', compact('parts', 'categories'));
    }

    /**
     * Menyimpan sparepart baru ke database.
     * Mengembalikan respon JSON untuk request AJAX.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang masuk
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'part_number' => 'required|string|max:255|unique:parts,part_number',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'minimum_stock' => 'required|integer|min:0',
                'condition' => 'required|in:new,used,refurbished',
                'specifications' => 'nullable|string',
                'notes' => 'nullable|string',
                'is_active' => 'boolean', // Validasi ini akan memastikan nilai adalah boolean yang valid (0 atau 1)
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar (2MB)
            ]);

            // --- PERBAIKAN UNTUK is_active ---
            // Gunakan $request->boolean() untuk mendapatkan nilai boolean yang benar dari checkbox
            $validatedData['is_active'] = $request->boolean('is_active');

            // Tangani spesifikasi
            if ($request->filled('specifications')) {
                $decodedSpecs = json_decode($validatedData['specifications'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validatedData['specifications'] = json_encode($decodedSpecs);
                }
            } else {
                $validatedData['specifications'] = null;
            }

            // Tangani upload gambar
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('parts', 'public'); // Simpan di storage/app/public/parts
                $validatedData['image'] = $imagePath;
            } else {
                $validatedData['image'] = null; // Pastikan kolom image null jika tidak ada file
            }

            $part = Part::create($validatedData);

            return response()->json(['message' => 'Sparepart berhasil ditambahkan.', 'part' => $part], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Validasi gagal.'], 422);
        } 
    }

    /**
     * Memperbarui sparepart yang ada di database.
     * Mengembalikan respon JSON untuk request AJAX.
     *
     * @param Request $request
     * @param Part $part
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Part $part)
    {
        try {
            // Validasi data yang masuk
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'part_number' => ['required', 'string', 'max:255', Rule::unique('parts', 'part_number')->ignore($part->id)],
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|integer|min:0',
                'minimum_stock' => 'required|integer|min:0',
                'condition' => 'required|in:new,used,refurbished',
                'specifications' => 'nullable|string',
                'notes' => 'nullable|string',
                'is_active' => 'boolean', // Validasi ini akan memastikan nilai adalah boolean yang valid (0 atau 1)
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            ]);

            // --- PERBAIKAN UNTUK is_active ---
            // Gunakan $request->boolean() untuk mendapatkan nilai boolean yang benar dari checkbox
            $validatedData['is_active'] = $request->boolean('is_active');

            // Tangani spesifikasi
            if ($request->filled('specifications')) {
                $decodedSpecs = json_decode($validatedData['specifications'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $validatedData['specifications'] = json_encode($decodedSpecs);
                }
            } else {
                $validatedData['specifications'] = null;
            }

            // Tangani upload gambar baru atau penghapusan gambar lama
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($part->image) {
                    Storage::disk('public')->delete($part->image);
                }
                $imagePath = $request->file('image')->store('parts', 'public');
                $validatedData['image'] = $imagePath;
            } elseif ($request->has('remove_current_image') && $request->input('remove_current_image') === '1') {
                // Jika tombol "Hapus Gambar" ditekan dari frontend
                if ($part->image) {
                    Storage::disk('public')->delete($part->image);
                }
                $validatedData['image'] = null;
            } else {
                // Jika tidak ada upload baru dan tidak ada permintaan hapus, pertahankan gambar lama
                $validatedData['image'] = $part->image;
            }

            $part->update($validatedData);

            return response()->json(['message' => 'Sparepart berhasil diperbarui.', 'part' => $part], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Validasi gagal.'], 422);
        } 
    }

    /**
     * Menghapus sparepart dari database.
     * Mengembalikan respon JSON untuk request AJAX.
     *
     * @param Part $part
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Part $part)
    {
        try {
            // Hapus gambar terkait jika ada
            if ($part->image) {
                Storage::disk('public')->delete($part->image);
            }
            $part->delete();
            return response()->json(['message' => 'Sparepart berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus sparepart.', 'error' => $e->getMessage()], 500);
        }
    }
}
