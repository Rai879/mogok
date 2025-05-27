@extends('layouts.app')

@section('title', 'Daftar Kecocokan')

@section('content')
    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Daftar Kecocokan</h4>
                    <form action="{{ route('compatibles.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control me-2" placeholder="Cari Kecocokan..."
                            value="{{ request('search') }}">
                        <!-- Hidden inputs untuk mempertahankan sorting saat search -->
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        <input type="hidden" name="direction" value="{{ request('direction') }}">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                    </form>
                    <a href="{{ route('compatibles.create') }}" class="btn btn-primary">Tambah Kecocokan</a>
                </div>

                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <a href="{{ route('compatibles.index', array_merge(request()->query(), ['sort' => 'part_name', 'direction' => request('sort') == 'part_name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="sortable-header {{ request('sort') == 'part_name' ? 'active' : '' }}">
                                    Part
                                    @if(request('sort') == 'part_name')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('compatibles.index', array_merge(request()->query(), ['sort' => 'vehicle_make', 'direction' => request('sort') == 'vehicle_make' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="sortable-header {{ request('sort') == 'vehicle_make' ? 'active' : '' }}">
                                    Merk
                                    @if(request('sort') == 'vehicle_make')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('compatibles.index', array_merge(request()->query(), ['sort' => 'vehicle_model', 'direction' => request('sort') == 'vehicle_model' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="sortable-header {{ request('sort') == 'vehicle_model' ? 'active' : '' }}">
                                    Model
                                    @if(request('sort') == 'vehicle_model')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('compatibles.index', array_merge(request()->query(), ['sort' => 'vehicle_year', 'direction' => request('sort') == 'vehicle_year' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="sortable-header {{ request('sort') == 'vehicle_year' ? 'active' : '' }}">
                                    Tahun
                                    @if(request('sort') == 'vehicle_year')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('compatibles.index', array_merge(request()->query(), ['sort' => 'engine_type', 'direction' => request('sort') == 'engine_type' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="sortable-header {{ request('sort') == 'engine_type' ? 'active' : '' }}">
                                    Mesin
                                    @if(request('sort') == 'engine_type')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('compatibles.index', array_merge(request()->query(), ['sort' => 'is_verified', 'direction' => request('sort') == 'is_verified' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="sortable-header {{ request('sort') == 'is_verified' ? 'active' : '' }}">
                                    Verifikasi
                                    @if(request('sort') == 'is_verified')
                                        @if(request('direction') == 'asc')
                                            <i class="fas fa-sort-up"></i>
                                        @else
                                            <i class="fas fa-sort-down"></i>
                                        @endif
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
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
                 <!-- Custom Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $compatibles->firstItem() ?? 0 }} hingga {{ $compatibles->lastItem() ?? 0 }} 
                            dari {{ $compatibles->total() }} hasil
                        </small>
                    </div>
                    <div>
                        @if ($compatibles->hasPages())
                            <nav aria-label="Pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($compatibles->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $compatibles->appends(request()->query())->previousPageUrl() }}">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- First Page --}}
                                    @if ($compatibles->currentPage() > 3)
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $compatibles->appends(request()->query())->url(1) }}">1</a>
                                        </li>
                                        @if ($compatibles->currentPage() > 4)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Page Numbers --}}
                                    @for ($i = max(1, $compatibles->currentPage() - 2); $i <= min($compatibles->lastPage(), $compatibles->currentPage() + 2); $i++)
                                        @if ($i == $compatibles->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $compatibles->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Last Page --}}
                                    @if ($compatibles->currentPage() < $compatibles->lastPage() - 2)
                                        @if ($compatibles->currentPage() < $compatibles->lastPage() - 3)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $compatibles->appends(request()->query())->url($compatibles->lastPage()) }}">{{ $compatibles->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($compatibles->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $compatibles->appends(request()->query())->nextPageUrl() }}">
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
                    
                    /* Sortable header styles */
                    .sortable-header {
                        color: #495057;
                        text-decoration: none;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        width: 100%;
                        padding: 0;
                        transition: color 0.15s ease-in-out;
                    }
                    
                    .sortable-header:hover {
                        color: #0d6efd;
                        text-decoration: none;
                    }
                    
                    .sortable-header.active {
                        color: #0d6efd;
                        font-weight: 600;
                    }
                    
                    .sortable-header i {
                        margin-left: 8px;
                        font-size: 0.8rem;
                        opacity: 0.7;
                    }
                    
                    .sortable-header:hover i {
                        opacity: 1;
                    }
                    
                    .sortable-header.active i {
                        opacity: 1;
                        color: #0d6efd;
                    }
                    
                    th {
                        padding: 0.75rem;
                        vertical-align: middle;
                    }
                </style>
            </span>
        </div>
    </div>
@endsection