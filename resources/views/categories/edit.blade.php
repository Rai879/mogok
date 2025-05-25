@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">
                <h4 class="mb-3">Edit Kategori</h4>

                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ $category->slug }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description"
                            class="form-control">{{ $category->description }}</textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Aktif
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </span>
        </div>
    </div>
@endsection