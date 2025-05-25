@extends('layouts.app')

@section('title', 'Tambah Kecocokan')

@section('content')
    <h4 class="mb-3">Tambah Kecocokan</h4>

    <form action="{{ route('compatibles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="part_id" class="form-label">Nama Part</label>
            <select name="part_id" id="part_id" class="form-select" required>
                @foreach($parts as $part)
                    <option value="{{ $part->id }}">{{ $part->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="vehicle_make" class="form-label">Merk Kendaraan</label>
            <input type="text" name="vehicle_make" id="vehicle_make" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="vehicle_model" class="form-label">Model Kendaraan</label>
            <input type="text" name="vehicle_model" id="vehicle_model" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="vehicle_year" class="form-label">Tahun Kendaraan</label>
            <input type="text" name="vehicle_year" id="vehicle_year" class="form-control">
        </div>
        <div class="mb-3">
            <label for="engine_type" class="form-label">Tipe Mesin</label>
            <input type="text" name="engine_type" id="engine_type" class="form-control">
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Catatan</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" value="1">
            <label class="form-check-label" for="is_verified">Terverifikasi</label>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('compatibles.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
