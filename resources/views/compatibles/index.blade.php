@extends('layouts.app')

@section('title', 'Daftar Kecocokan')

@section('content')
    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Daftar Kecocokan</h4>
                    <a href="{{ route('compatibles.create') }}" class="btn btn-primary">Tambah Kecocokan</a>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Part</th>
                            <th>Merk</th>
                            <th>Model</th>
                            <th>Tahun</th>
                            <th>Mesin</th>
                            <th>Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compatibles as $compatible)
                            <tr>
                                <td>{{ $compatible->part->name }}</td>
                                <td>{{ $compatible->vehicle_make }}</td>
                                <td>{{ $compatible->vehicle_model }}</td>
                                <td>{{ $compatible->vehicle_year }}</td>
                                <td>{{ $compatible->engine_type }}</td>
                                <td>{{ $compatible->is_verified ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a href="{{ route('compatibles.edit', $compatible->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('compatibles.destroy', $compatible->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kecocokan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </span>
        </div>
    </div>
@endsection