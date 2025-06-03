@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Part Barcode</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('part-barcodes.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="part_id" class="form-label">Part</label>
            <select class="form-control" id="part_id" name="part_id" required>
                <option value="">Select a Part</option>
                @foreach ($parts as $part)
                    <option value="{{ $part->id }}">{{ $part->name }} ({{ $part->part_number }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input type="text" class="form-control" id="barcode" name="barcode" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Barcode</button>
        <a href="{{ route('part-barcodes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection