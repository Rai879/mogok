@extends('layouts.app')

@section('title', 'Daftar Kecocokan')

@section('content')
    <div class="container">
        <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-bicycle fa-2x me-2"></i>
                        <h1 class="mb-0">Daftar Kecocokan</h1>
                    </div>
                </div>

                {{-- Form for adding and updating compatibles --}}
                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <h5 id="formTitleAddEdit">Tambah Kecocokan Baru</h5>
                    <form id="compatibleForm" action="{{ route('compatibles.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="compatible_id" id="compatibleId">
                        <input type="hidden" name="part_id" id="selectedPartId"> {{-- Hidden input for part_id --}}

                        <div class="row">
                            <div class="col-md-6 mb-3 position-relative"> {{-- Added position-relative for suggestions --}}
                                <label for="partNameSearch" class="form-label">Nama Part</label>
                                <input type="text" class="form-control" id="partNameSearch" placeholder="Cari nama part..."
                                    required>
                                <div id="partSuggestions" class="list-group position-absolute w-100" style="z-index: 1000;">
                                    {{-- Suggestions will be appended here by JS --}}
                                </div>
                                @error('part_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_make" class="form-label">Merk Kendaraan</label>
                                <input type="text" class="form-control" id="vehicle_make" name="vehicle_make" required>
                                @error('vehicle_make')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_model" class="form-label">Model Kendaraan</label>
                                <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" required>
                                @error('vehicle_model')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vehicle_year" class="form-label">Tahun Kendaraan</label>
                                <input type="number" class="form-control" id="vehicle_year" name="vehicle_year"
                                    placeholder="YYYY" min="1900" max="{{ date('Y') + 5 }}" required>
                                @error('vehicle_year')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="engine_type" class="form-label">Tipe Mesin (Opsional)</label>
                            <input type="text" class="form-control" id="engine_type" name="engine_type">
                            @error('engine_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="is_verified" name="is_verified"
                                checked>
                            <label class="form-check-label" for="is_verified">
                                Verifikasi
                            </label>
                            @error('is_verified')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="submitButton">Tambah Kecocokan</button>
                        <button type="button" class="btn btn-secondary" id="cancelButton"
                            style="display: none;">Batal</button>
                    </form>
                </div>

                {{-- Table of compatibles --}}
                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="formTitleAddEdit">Daftar Kecocokan</h5>
                        <form id="searchForm" method="GET" class="d-flex">
                            <input type="text" name="search" id="searchInput" class="form-control me-2"
                            placeholder="Cari Kecocokan..." value="{{ request('search') }}">
                        </form>
                    </div>
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
                            <tbody id="compatibleTableBody"> {{-- Add ID for JavaScript filtering --}}
                                @forelse($compatibles as $compatible)
                                    <tr>
                                        <td>{{ $compatible->part->name }}</td>
                                        <td>{{ $compatible->vehicle_make }}</td>
                                        <td>{{ $compatible->vehicle_model }}</td>
                                        <td>{{ $compatible->vehicle_year }}</td>
                                        <td>{{ $compatible->engine_type ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $compatible->is_verified ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $compatible->is_verified ? 'Ya' : 'Tidak' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column flex-sm-row gap-1">
                                                <button type="button" class="btn btn-sm btn-warning edit-button"
                                                    data-id="{{ $compatible->id }}" data-part_id="{{ $compatible->part_id }}"
                                                    data-part_name="{{ $compatible->part->name }}"
                                                    data-vehicle_make="{{ $compatible->vehicle_make }}"
                                                    data-vehicle_model="{{ $compatible->vehicle_model }}"
                                                    data-vehicle_year="{{ $compatible->vehicle_year }}"
                                                    data-engine_type="{{ $compatible->engine_type }}"
                                                    data-is_verified="{{ $compatible->is_verified }}">Edit</button>

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
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data kecocokan ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination controls --}}
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
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Elements for Add/Edit Form ---
            const compatibleForm = document.getElementById('compatibleForm');
            const formTitle = document.getElementById('formTitleAddEdit');
            const submitButton = document.getElementById('submitButton');
            const cancelButton = document.getElementById('cancelButton');
            const compatibleIdInput = document.getElementById('compatibleId');
            const formMethodInput = document.getElementById('formMethod');
            const selectedPartIdInput = document.getElementById('selectedPartId'); // Hidden part_id
            const partNameSearchInput = document.getElementById('partNameSearch'); // Search input for part name
            const partSuggestionsDiv = document.getElementById('partSuggestions');
            const vehicleMakeInput = document.getElementById('vehicle_make');
            const vehicleModelInput = document.getElementById('vehicle_model');
            const vehicleYearInput = document.getElementById('vehicle_year');
            const engineTypeInput = document.getElementById('engine_type');
            const isVerifiedCheckbox = document.getElementById('is_verified');

            // --- Elements for Real-time Table Search ---
            const searchInput = document.getElementById('searchInput');
            const compatibleTableBody = document.getElementById('compatibleTableBody');
            const tableRows = compatibleTableBody.querySelectorAll('tr'); // Initial set of rows

            // --- Get all parts data from Laravel for autocomplete ---
            // You MUST pass $parts from your CompatibleController's index method
            // Example in Controller: return view('compatibles.index', compact('compatibles', 'parts'));
            const allParts = @json($parts ?? []);

            // --- Part Name Search (Autocomplete) Logic ---
            partNameSearchInput.addEventListener('keyup', function () {
                const query = this.value.trim().toLowerCase();
                partSuggestionsDiv.innerHTML = ''; // Clear previous suggestions
                selectedPartIdInput.value = ''; // Clear selected part ID if input changes

                if (query.length > 0) {
                    const filteredParts = allParts.filter(part =>
                        part.name.toLowerCase().includes(query)
                    );

                    if (filteredParts.length > 0) {
                        filteredParts.forEach(part => {
                            const suggestionItem = document.createElement('a');
                            suggestionItem.href = '#';
                            suggestionItem.classList.add('list-group-item', 'list-group-item-action');
                            suggestionItem.textContent = part.name;
                            suggestionItem.dataset.id = part.id;
                            suggestionItem.dataset.name = part.name;

                            suggestionItem.addEventListener('click', function (e) {
                                e.preventDefault();
                                partNameSearchInput.value = this.dataset.name;
                                selectedPartIdInput.value = this.dataset.id;
                                partSuggestionsDiv.innerHTML = ''; // Hide suggestions
                            });
                            partSuggestionsDiv.appendChild(suggestionItem);
                        });
                    } else {
                        // No results, show a "not found" message
                        const noResultsItem = document.createElement('div');
                        noResultsItem.classList.add('list-group-item', 'text-muted');
                        noResultsItem.textContent = 'Tidak ada part ditemukan.';
                        partSuggestionsDiv.appendChild(noResultsItem);
                    }
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function (e) {
                if (!partNameSearchInput.contains(e.target) && !partSuggestionsDiv.contains(e.target)) {
                    partSuggestionsDiv.innerHTML = '';
                }
            });
            // --- End Part Name Search (Autocomplete) Logic ---


            // --- Edit Button Logic ---
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const part_id = this.dataset.part_id;
                    const part_name = this.dataset.part_name;
                    const vehicle_make = this.dataset.vehicle_make;
                    const vehicle_model = this.dataset.vehicle_model;
                    const vehicle_year = this.dataset.vehicle_year;
                    const engine_type = this.dataset.engine_type;
                    const is_verified = this.dataset.is_verified === '1'; // Convert to boolean

                    // Update form action and method for PUT request
                    compatibleForm.action = `/compatibles/${id}`; // Adjust if your route differs
                    formMethodInput.value = 'PUT';
                    compatibleIdInput.value = id;

                    // Fill form fields
                    partNameSearchInput.value = part_name;
                    selectedPartIdInput.value = part_id;
                    vehicleMakeInput.value = vehicle_make;
                    vehicleModelInput.value = vehicle_model;
                    vehicleYearInput.value = vehicle_year;
                    engineTypeInput.value = engine_type;
                    isVerifiedCheckbox.checked = is_verified;

                    // Update button text and form title
                    formTitle.textContent = 'Edit Kecocokan';
                    submitButton.textContent = 'Update Kecocokan';
                    submitButton.classList.remove('btn-primary');
                    submitButton.classList.add('btn-success');
                    cancelButton.style.display = 'inline-block'; // Show cancel button
                });
            });

            // --- Cancel Button Logic ---
            cancelButton.addEventListener('click', function () {
                // Reset form to add new
                compatibleForm.action = "{{ route('compatibles.store') }}";
                formMethodInput.value = 'POST';
                compatibleIdInput.value = '';
                selectedPartIdInput.value = ''; // Clear selected part ID
                compatibleForm.reset(); // Clear all form fields
                isVerifiedCheckbox.checked = true; // Ensure verified is checked by default for new

                partSuggestionsDiv.innerHTML = ''; // Clear any open suggestions

                // Reset button text and form title
                formTitle.textContent = 'Tambah Kecocokan Baru';
                submitButton.textContent = 'Tambah Kecocokan';
                submitButton.classList.remove('btn-success');
                submitButton.classList.add('btn-primary');
                cancelButton.style.display = 'none'; // Hide cancel button
            });

            // --- Real-time Search Logic for Table ---
            searchInput.addEventListener('keyup', function () {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    // Get text from relevant columns. Adjust indices if your table structure changes.
                    const partName = row.children[0].textContent.toLowerCase();
                    const vehicleMake = row.children[1].textContent.toLowerCase();
                    const vehicleModel = row.children[2].textContent.toLowerCase();
                    const vehicleYear = row.children[3].textContent.toLowerCase();
                    const engineType = row.children[4].textContent.toLowerCase();
                    const isVerifiedText = row.children[5].textContent.toLowerCase(); // 'ya' or 'tidak'

                    if (partName.includes(searchTerm) ||
                        vehicleMake.includes(searchTerm) ||
                        vehicleModel.includes(searchTerm) ||
                        vehicleYear.includes(searchTerm) ||
                        engineType.includes(searchTerm) ||
                        isVerifiedText.includes(searchTerm)
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