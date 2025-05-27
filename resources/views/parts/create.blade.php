@extends('layouts.app')

@section('title', 'Tambah Sparepart')

@section('content')
<div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
    <div class="card-body">
        <span class="rounded">
        <h4>Tambah Sparepart</h4>
        <br>

        {{-- Notifikasi Error Global --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Ada kesalahan pada input Anda:
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('parts.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Nomor Part</label>
                <input type="text" name="part_number" class="form-control @error('part_number') is-invalid @enderror" value="{{ old('part_number') }}" required>
                @error('part_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity') }}" required>
                @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Minimal Stok</label>
                <input type="number" name="minimum_stock" class="form-control @error('minimum_stock') is-invalid @enderror" value="{{ old('minimum_stock') }}" required>
                @error('minimum_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Kondisi</label>
                <select name="condition" class="form-select @error('condition') is-invalid @enderror" required>
                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>Baru</option>
                    <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>Bekas</option>
                    <option value="refurbished" {{ old('condition') == 'refurbished' ? 'selected' : '' }}>Refurbished</option>
                </select>
                @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Brand</label>
                <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}">
                @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Spesifikasi (JSON)</label>
                <textarea name="specifications" class="form-control @error('specifications') is-invalid @enderror">{{ old('specifications') }}</textarea>
                @error('specifications') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" {{ old('is_active',false) ? 'checked' : '' }}>
                <label class="form-check-label">Aktif</label>
                {{-- Tidak perlu error block karena checkbox tidak wajib --}}
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('parts.index') }}" class="btn btn-secondary">Batal</a>
        </form>
        </span>
    </div>
</div>
@endsection
