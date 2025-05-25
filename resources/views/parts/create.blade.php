@extends('layouts.app')

@section('title', 'Tambah Sparepart')


@section('content')
<div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
    <div class="card-body">
        <span class="rounded">
        <h4>Tambah Sparepart</h4>
        <br>
        <form action="{{ route('parts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nomor Part</label>
                <input type="text" name="part_number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="price" step="0.01" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stock_quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Minimal Stok</label>
                <input type="number" name="minimum_stock" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kondisi</label>
                <select name="condition" class="form-select" required>
                    <option value="new">Baru</option>
                    <option value="used">Bekas</option>
                    <option value="refurbished">Refurbished</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Brand</label>
                <input type="text" name="brand" class="form-control">
            </div>
            <div class="mb-3">
                <label>Spesifikasi (JSON)</label>
                <textarea name="specifications" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" checked>
                <label class="form-check-label">Aktif</label>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('parts.index') }}" class="btn btn-secondary">Batal</a>
        </form>
        </span>
    </div>
</div>
@endsection
