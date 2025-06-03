@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Part Barcodes</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('part-barcodes.create') }}" class="btn btn-primary mb-3">Add New Barcode</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Part Name</th>
                <th>Barcode</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($partBarcodes as $partBarcode)
                <tr>
                    <td>{{ $partBarcode->id }}</td>
                    <td>{{ $partBarcode->part->name }}</td>
                    <td>{{ $partBarcode->barcode }}</td>
                    <td>
                        <a href="{{ route('part-barcodes.edit', $partBarcode) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('part-barcodes.destroy', $partBarcode) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $partBarcodes->links() }}
</div>
@endsection