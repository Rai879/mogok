@extends('layouts.app')

@section('title', 'Edit Sparepart')

@section('content')
    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">

                <h1>Edit Sparepart</h1>
                <form action="{{ route('parts.update', $part) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $part->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Nomor Part</label>
                        <input type="text" name="part_number" class="form-control" value="{{ $part->part_number }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="category_id" class="form-select" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $part->category_id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="price" step="0.01" class="form-control" value="{{ $part->price }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <input type="number" name="stock_quantity" class="form-control" value="{{ $part->stock_quantity }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Minimal Stok</label>
                        <input type="number" name="minimum_stock" class="form-control" value="{{ $part->minimum_stock }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Kondisi</label>
                        <select name="condition" class="form-select" required>
                            <option value="new" {{ $part->condition == 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="used" {{ $part->condition == 'used' ? 'selected' : '' }}>Bekas</option>
                            <option value="refurbished" {{ $part->condition == 'refurbished' ? 'selected' : '' }}>Refurbished
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control" value="{{ $part->brand }}">
                    </div>
                    <div class="mb-3">
                        <label>Spesifikasi (JSON)</label>
                        <textarea name="specifications" class="form-control">{{ $part->specifications }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="notes" class="form-control">{{ $part->notes }}</textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $part->is_active ? 'checked' : '' }}>
                        <label class="form-check-label">Aktif</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('parts.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </span>
        </div>
    </div>
@endsection