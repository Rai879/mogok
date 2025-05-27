@extends('layouts.app')

@section('title', 'Sparepart')

@section('content')

    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Daftar Part</h4>
                    <form action="{{ route('parts.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari sparepart..."
                            value="{{ request('search') }}">
                        <!-- Preserve sorting parameters when searching -->
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                    </form>
                    <a href="{{ route('parts.create') }}" class="btn btn-success">Tambah Parts</a>
                </div>
                
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('parts.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
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
                                <a href="{{ route('parts.index', array_merge(request()->query(), ['sort' => 'category_id', 'direction' => request('sort') == 'category_id' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Kategori 
                                    @if(request('sort') == 'category_id')
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
                                <a href="{{ route('parts.index', array_merge(request()->query(), ['sort' => 'price', 'direction' => request('sort') == 'price' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Harga 
                                    @if(request('sort') == 'price')
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
                                <a href="{{ route('parts.index', array_merge(request()->query(), ['sort' => 'stock_quantity', 'direction' => request('sort') == 'stock_quantity' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Stok 
                                    @if(request('sort') == 'stock_quantity')
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
                                <a href="{{ route('parts.index', array_merge(request()->query(), ['sort' => 'is_active', 'direction' => request('sort') == 'is_active' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-decoration-none text-dark">
                                    Status 
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
                        @forelse ($parts as $part)
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Custom Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $parts->firstItem() ?? 0 }} hingga {{ $parts->lastItem() ?? 0 }} 
                            dari {{ $parts->total() }} hasil
                        </small>
                    </div>
                    <div>
                        @if ($parts->hasPages())
                            <nav aria-label="Pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($parts->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $parts->appends(request()->query())->previousPageUrl() }}">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- First Page --}}
                                    @if ($parts->currentPage() > 3)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $parts->appends(request()->query())->url(1) }}">1</a>
                                        </li>
                                        @if ($parts->currentPage() > 4)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($i = max(1, $parts->currentPage() - 2); $i <= min($parts->lastPage(), $parts->currentPage() + 2); $i++)
                                        @if ($i == $parts->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $parts->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Last Page --}}
                                    @if ($parts->currentPage() < $parts->lastPage() - 2)
                                        @if ($parts->currentPage() < $parts->lastPage() - 3)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $parts->appends(request()->query())->url($parts->lastPage()) }}">{{ $parts->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($parts->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $parts->appends(request()->query())->nextPageUrl() }}">
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
                </style>
                
            </span>
        </div>
    </div>
@endsection