@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-barcode fa-2x me-2"></i>
                    <h1>Part Barcodes</h1>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <a href="{{ route('part-barcodes.create') }}" class="btn btn-primary mb-3">Add New Barcode</a>

                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th>Part Name</th>
                            <th>Barcode</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($partBarcodes as $partBarcode)
                            <tr>
                                <td>{{ $partBarcode->part->name }}</td>
                                <td>{{ $partBarcode->barcode }}</td>
                                <td>
                                    <a href="{{ route('part-barcodes.edit', $partBarcode) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('part-barcodes.destroy', $partBarcode) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $partBarcodes->links() }}
            </div>
        </div>
    </div>
@endsection