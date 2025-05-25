@extends('layouts.app')

@section('title', 'Sparepart')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Parts</li>
@endsection

@section('content')

    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Sparepart</h4>
                    <a href="{{ route('parts.create') }}" class="btn btn-success">Tambah Sparepart</a>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parts as $part)
                            <tr>
                                <td>{{ $part->name }}</td>
                                <td>{{ $part->category->name ?? '-' }}</td>
                                <td>Rp {{ number_format($part->price, 0, ',', '.') }}</td>
                                <td>{{ $part->stock_quantity }}</td>
                                <td><span class="badge {{ $part->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $part->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td>
                                    <a href="{{ route('parts.edit', $part) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('parts.destroy', $part) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
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