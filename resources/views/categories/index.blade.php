@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Daftar Kategori</h4>
                    <form action="{{ route('categories.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Kategori..."
                            value="{{ request('search') }}">
                        <!-- Preserve sorting parameters when searching -->
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                    </form>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary">Tambah Kategori</a>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Nama 
                                    @if(request('sort') == 'name')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'slug', 'direction' => request('sort') == 'slug' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Slug 
                                    @if(request('sort') == 'slug')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'description', 'direction' => request('sort') == 'description' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Deskripsi 
                                    @if(request('sort') == 'description')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('categories.index', array_merge(request()->query(), ['sort' => 'is_active', 'direction' => request('sort') == 'is_active' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Aktif 
                                    @if(request('sort') == 'is_active')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ Str::limit($category->description, 50) ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $category->is_active ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Custom Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $categories->firstItem() ?? 0 }} hingga {{ $categories->lastItem() ?? 0 }} 
                            dari {{ $categories->total() }} hasil
                        </small>
                    </div>
                    <div>
                        @if ($categories->hasPages())
                            <nav aria-label="Pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($categories->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $categories->appends(request()->query())->previousPageUrl() }}">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- First Page --}}
                                    @if ($categories->currentPage() > 3)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $categories->appends(request()->query())->url(1) }}">1</a>
                                        </li>
                                        @if ($categories->currentPage() > 4)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($i = max(1, $categories->currentPage() - 2); $i <= min($categories->lastPage(), $categories->currentPage() + 2); $i++)
                                        @if ($i == $categories->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $categories->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Last Page --}}
                                    @if ($categories->currentPage() < $categories->lastPage() - 2)
                                        @if ($categories->currentPage() < $categories->lastPage() - 3)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $categories->appends(request()->query())->url($categories->lastPage()) }}">{{ $categories->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($categories->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $categories->appends(request()->query())->nextPageUrl() }}">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>

                <style>
                    .pagination-sm .page-link {
                        padding: 0.375rem 0.75rem;
                        font-size: 0.875rem;
                        border-radius: 0.375rem;
                        margin: 0 2px;
                        border: 1px solid #dee2e6;
                        color: #6c757d;
                        transition: all 0.15s ease-in-out;
                    }
                    
                    .pagination-sm .page-link:hover {
                        background-color: #e9ecef;
                        border-color: #adb5bd;
                        color: #495057;
                        transform: translateY(-1px);
                    }
                    
                    .pagination-sm .page-item.active .page-link {
                        background-color: #0d6efd;
                        border-color: #0d6efd;
                        color: white;
                        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
                    }
                    
                    .pagination-sm .page-item.disabled .page-link {
                        color: #adb5bd;
                        background-color: #fff;
                        border-color: #dee2e6;
                        cursor: not-allowed;
                    }
                    
                    .pagination-sm .page-item:first-child .page-link,
                    .pagination-sm .page-item:last-child .page-link {
                        border-radius: 0.375rem;
                    }
                    
                    .pagination {
                        gap: 2px;
                    }
                    
                    .page-link i {
                        font-size: 0.75rem;
                    }
                    
                    /* Sorting header styles */
                    th a {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        width: 100%;
                    }
                    
                    th a:hover {
                        background-color: rgba(0, 0, 0, 0.05);
                        border-radius: 4px;
                        padding: 4px 8px;
                        margin: -4px -8px;
                    }
                </style>
            </span>
        </div>
    </div>
@endsection