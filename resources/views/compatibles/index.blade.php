@extends('layouts.app')

@section('title', 'Daftar Kecocokan')

@section('content')
    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-bicycle fa-2x me-2"></i>
                        <h1 class="mb-0">Daftar Kecocokan</h1>
                    </div>
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

                <!-- Wrapper responsif untuk tabel -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <a href="{{ route('compatibles.index', array_merge(request()->query(), ['sort' => 'part_name', 'direction' => request('sort') == 'part_name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                        class="text-decoration-none text-dark">
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
                                        class="text-decoration-none text-dark">
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
                                        class="text-decoration-none text-dark">
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
                                        class="text-decoration-none text-dark">
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
                                        class="text-decoration-none text-dark">
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
                                        class="text-decoration-none text-dark">
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
                                        <div class="d-flex flex-column flex-sm-row gap-1">
                                            <a href="{{ route('compatibles.edit', $compatible->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('compatibles.destroy', $compatible->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus kecocokan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Custom Pagination -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mt-3 gap-2">
                    <div class="order-2 order-sm-1">
                        <small class="text-muted">
                            Menampilkan {{ $compatibles->firstItem() ?? 0 }} hingga {{ $compatibles->lastItem() ?? 0 }}
                            dari {{ $compatibles->total() }} hasil
                        </small>
                    </div>
                    <div class="order-1 order-sm-2">
                        @if ($compatibles->hasPages())
                            <nav aria-label="Pagination">
                                <ul class="pagination pagination-sm mb-0 justify-content-center">
                                    {{-- Previous Page Link --}}
                                    @if ($compatibles->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $compatibles->appends(request()->query())->previousPageUrl() }}">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- First Page --}}
                                    @if ($compatibles->currentPage() > 3)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $compatibles->appends(request()->query())->url(1) }}">1</a>
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
                                                <a class="page-link"
                                                    href="{{ $compatibles->appends(request()->query())->url($i) }}">{{ $i }}</a>
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
                                            <a class="page-link"
                                                href="{{ $compatibles->appends(request()->query())->url($compatibles->lastPage()) }}">{{ $compatibles->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Next Page Link --}}
                                    @if ($compatibles->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $compatibles->appends(request()->query())->nextPageUrl() }}">
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
            </span>
        </div>
    </div>
@endsection