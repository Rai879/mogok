@extends('layouts.app')

@section('title', 'Daftar Kategori')

@section('content')
    <div class="container">
        <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-list fa-2x me-2"></i>
                        <h1 class="mb-0">Daftar Kategori</h1>
                    </div>
                </div>
                {{-- Form for adding and updating categories --}}
                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <h5 id="formTitleAddEdit">Tambah Kategori Baru</h5>
                    <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="category_id" id="categoryId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" readonly> {{-- Slug will be
                                auto-generated --}}
                                @error('slug')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
                                checked>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                            @error('is_active')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitButton">Tambah Kategori</button>
                        <button type="button" class="btn btn-secondary" id="cancelButton"
                            style="display: none;">Batal</button>
                    </form>
                </div>

                {{-- Table of categories --}}
                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="formTitleAddEdit">Daftar Kategori</h5>
                        <form id="searchForm" method="GET" class="d-flex">
                            <input type="text" name="search" id="searchInput" class="form-control me-2"
                                placeholder="Cari Kategori..." value="{{ request('search') }}">
                        </form>
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
                        <tbody id="categoryTableBody"> {{-- Add ID for JavaScript filtering --}}
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
                                        <button type="button" class="btn btn-sm btn-warning edit-button"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                            data-slug="{{ $category->slug }}" data-description="{{ $category->description }}"
                                            data-is_active="{{ $category->is_active }}">Edit</button>

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
                    {{-- Pagination controls --}}
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
                                                <a class="page-link"
                                                    href="{{ $categories->appends(request()->query())->previousPageUrl() }}">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- First Page --}}
                                        @if ($categories->currentPage() > 3)
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $categories->appends(request()->query())->url(1) }}">1</a>
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
                                                    <a class="page-link"
                                                        href="{{ $categories->appends(request()->query())->url($i) }}">{{ $i }}</a>
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
                                                <a class="page-link"
                                                    href="{{ $categories->appends(request()->query())->url($categories->lastPage()) }}">{{ $categories->lastPage() }}</a>
                                            </li>
                                        @endif

                                        {{-- Next Page Link --}}
                                        @if ($categories->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $categories->appends(request()->query())->nextPageUrl() }}">
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Elements for Add/Edit Form ---
            const categoryForm = document.getElementById('categoryForm');
            const formTitle = document.getElementById('formTitleAddEdit');
            const submitButton = document.getElementById('submitButton');
            const cancelButton = document.getElementById('cancelButton');
            const categoryIdInput = document.getElementById('categoryId');
            const formMethodInput = document.getElementById('formMethod');
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');
            const descriptionInput = document.getElementById('description');
            const isActiveCheckbox = document.getElementById('is_active');

            // --- Elements for Real-time Search ---
            const searchInput = document.getElementById('searchInput');
            const categoryTableBody = document.getElementById('categoryTableBody');
            const tableRows = categoryTableBody.querySelectorAll('tr'); // Initial set of rows

            // --- Slug Auto-generation (if 'name' changes) ---
            nameInput.addEventListener('keyup', function () {
                // Simple slug generation: replace spaces with hyphens, convert to lowercase
                const name = this.value.trim();
                const slug = name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                slugInput.value = slug;
            });


            // --- Edit Button Logic ---
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const slug = this.dataset.slug;
                    const description = this.dataset.description;
                    const isActive = this.dataset.is_active === '1'; // Convert to boolean

                    // Update form action and method for PUT request
                    categoryForm.action = `/categories/${id}`; // Assuming your update route is /categories/{category}
                    formMethodInput.value = 'PUT';
                    categoryIdInput.value = id;

                    // Fill form fields
                    nameInput.value = name;
                    slugInput.value = slug;
                    descriptionInput.value = description;
                    isActiveCheckbox.checked = isActive; // Set checkbox state

                    // Update button text and form title
                    formTitle.textContent = 'Edit Kategori';
                    submitButton.textContent = 'Update Kategori';
                    submitButton.classList.remove('btn-primary');
                    submitButton.classList.add('btn-success');
                    cancelButton.style.display = 'inline-block'; // Show cancel button
                });
            });

            // --- Cancel Button Logic ---
            cancelButton.addEventListener('click', function () {
                // Reset form to add new
                categoryForm.action = "{{ route('categories.store') }}";
                formMethodInput.value = 'POST';
                categoryIdInput.value = '';
                categoryForm.reset(); // Clear all form fields
                isActiveCheckbox.checked = true; // Ensure active is checked by default for new

                // Reset button text and form title
                formTitle.textContent = 'Tambah Kategori Baru';
                submitButton.textContent = 'Tambah Kategori';
                submitButton.classList.remove('btn-success');
                submitButton.classList.add('btn-primary');
                cancelButton.style.display = 'none'; // Hide cancel button
            });

            // --- Real-time Search Logic for Table ---
            searchInput.addEventListener('keyup', function () {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    // Get text from relevant columns. Adjust indices if your table structure changes.
                    const name = row.children[0].textContent.toLowerCase();
                    const slug = row.children[1].textContent.toLowerCase();
                    const description = row.children[2].textContent.toLowerCase();
                    const isActiveText = row.children[3].textContent.toLowerCase(); // 'ya' or 'tidak'

                    if (name.includes(searchTerm) ||
                        slug.includes(searchTerm) ||
                        description.includes(searchTerm) ||
                        isActiveText.includes(searchTerm)
                    ) {
                        row.style.display = ''; // Show row if any field matches
                    } else {
                        row.style.display = 'none'; // Hide row
                    }
                });
            });

            // Prevent search form submission (we handle filtering with JS)
            document.getElementById('searchForm').addEventListener('submit', function (e) {
                e.preventDefault();
            });
        });
    </script>
@endpush