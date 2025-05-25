@extends('layouts.app')

@section('title', 'Edit Kecocokan')

@section('content')
    <h4 class="mb-3">Edit Kecocokan</h4>

    <form action="{{ route('compatibles.update', $compatible->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="part_id" class="form-label">Nama Part</label>
            <select name="part_id" id="part_id" class="form-select" required>
                @foreach($parts as $part)
                    <option value="{{ $part->id }}" {{ $compatible->part_id == $part->id ? 'selected' : '' }}>
                        {{ $part->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="vehicle_make" class="form-label">Merk Kendaraan</label>
            <input type="text" name="vehicle_make" id="vehicle_make" class="form-control" value="{{ $compatible->vehicle_make }}" required>
        </div>
        <div class="mb-3">
            <label for="vehicle_model" class="form-label">Model Kendaraan</label>
            <input type="text" name="vehicle_model" id="vehicle_model" class="form-control" value="{{ $compatible->vehicle_model }}" required>
        </div>
        <div class="mb-3">
            <label for="vehicle_year" class="form-label">Tahun Kendaraan</label>
            <input type="text" name="vehicle_year" id="vehicle_year" class="form-control" value="{{ $compatible->vehicle_year }}">
        </div>
        <div class="mb-3">
            <label for="engine_type" class="form-label">Tipe Mesin</label>
            <input type="text" name="engine_type" id="engine_type" class="form-control" value="{{ $compatible->engine_type }}">
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Catatan</label>
            <textarea name="notes" id="notes" class="form-control">{{ $compatible->notes }}</textarea>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_verified" id="is_verified" value="1" {{ $compatible->is_verified ? 'checked' : '' }}>
            <label class="form-check-label" for="is_verified">Terverifikasi</label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('compatibles.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
