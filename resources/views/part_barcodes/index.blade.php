@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-barcode fa-2x me-2"></i>
                        <h1 class="mb-0">Barcode</h1>
                    </div>
                </div>
                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <h5 id="formTitleAddEdit">Tambah Barcode Baru</h5>
                    <form id="partBarcodeForm" action="{{ route('part-barcodes.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="part_barcode_id" id="partBarcodeId">
                        <input type="hidden" name="part_id" id="selectedPartId"> {{-- Hidden input for part_id --}}

                        <div class="row"> {{-- Add Bootstrap row for layout --}}
                            <div class="col-md-6 mb-3"> {{-- col-md-6 for Part Name --}}
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

                            <div class="col-md-6 mb-3"> {{-- col-md-6 for Barcode --}}
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode" required>
                                @error('barcode')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> {{-- End of row --}}

                        <button type="submit" class="btn btn-primary" id="submitButton">Tambah Barcode</button>
                        <button type="button" class="btn btn-secondary" id="cancelButton"
                            style="display: none;">Batal</button>
                    </form>
                </div>

                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="listTitle">Daftar Barcode</h5>
                        <form id="searchForm" class="d-flex mb-3 col-3" role="search">
                            <input class="form-control me-2" type="text" name="search" id="searchInput"
                                placeholder="Cari sparepart atau barcode..." aria-label="Search"
                                value="{{ request('search') }}">
                        </form>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Part Name</th>
                                <th>Barcode</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="barcodeTableBody">
                            @foreach ($partBarcodes as $partBarcode)
                                <tr>
                                    <td>{{ $partBarcode->part->name }}</td>
                                    <td>{{ $partBarcode->barcode }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning edit-button"
                                            data-id="{{ $partBarcode->id }}" data-part_id="{{ $partBarcode->part_id }}"
                                            data-part_name="{{ $partBarcode->part->name }}" {{-- Pass part name for edit --}}
                                            data-barcode="{{ $partBarcode->barcode }}">Edit</button>

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
                    <div class="d-flex justify-content-between align-items-center mt-3" id="paginationLinks">
                        <div>
                            <small class="text-muted">
                                Menampilkan {{ $partBarcodes->firstItem() ?? 0 }} hingga
                                {{ $partBarcodes->lastItem() ?? 0 }}
                                dari {{ $partBarcodes->total() }} hasil
                            </small>
                        </div>
                        <div>
                            @if ($partBarcodes->hasPages())
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Link Halaman Sebelumnya --}}
                                        @if ($partBarcodes->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $partBarcodes->appends(request()->query())->previousPageUrl() }}">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Halaman Pertama --}}
                                        @if ($partBarcodes->currentPage() > 3)
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $partBarcodes->appends(request()->query())->url(1) }}">1</a>
                                            </li>
                                            @if ($partBarcodes->currentPage() > 4)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                        @endif

                                        {{-- Nomor Halaman --}}
                                        @for ($i = max(1, $partBarcodes->currentPage() - 2); $i <= min($partBarcodes->lastPage(), $partBarcodes->currentPage() + 2); $i++)
                                            @if ($i == $partBarcodes->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $i }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $partBarcodes->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endfor

                                        {{-- Halaman Terakhir --}}
                                        @if ($partBarcodes->currentPage() < $partBarcodes->lastPage() - 2)
                                            @if ($partBarcodes->currentPage() < $partBarcodes->lastPage() - 3)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $partBarcodes->appends(request()->query())->url($partBarcodes->lastPage()) }}">{{ $partBarcodes->lastPage() }}</a>
                                            </li>
                                        @endif

                                        {{-- Link Halaman Berikutnya --}}
                                        @if ($partBarcodes->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $partBarcodes->appends(request()->query())->nextPageUrl() }}">
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
            const form = document.getElementById('partBarcodeForm');
            const formTitle = document.getElementById('formTitleAddEdit');
            const submitButton = document.getElementById('submitButton');
            const cancelButton = document.getElementById('cancelButton');
            const partBarcodeIdInput = document.getElementById('partBarcodeId');
            const formMethodInput = document.getElementById('formMethod');
            const barcodeInput = document.getElementById('barcode');

            // Elements for Part Search Autocomplete
            const partNameSearchInput = document.getElementById('partNameSearch');
            const selectedPartIdInput = document.getElementById('selectedPartId');
            const partSuggestionsDiv = document.getElementById('partSuggestions');
            const allParts = @json($parts); // Get all parts data from Laravel

            // Elements for Barcode Table Search
            const searchInput = document.getElementById('searchInput');
            const barcodeTableBody = document.getElementById('barcodeTableBody');
            const tableRows = barcodeTableBody.querySelectorAll('tr');

            // --- Part Name Search (Autocomplete) Logic ---
            partNameSearchInput.addEventListener('keyup', function () {
                const query = this.value.toLowerCase();
                partSuggestionsDiv.innerHTML = ''; // Clear previous suggestions

                if (query.length > 0) {
                    const filteredParts = allParts.filter(part =>
                        part.name.toLowerCase().includes(query)
                    );

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
                }
            });

            // Hide suggestions when clicking outside
            document.addEventListener('click', function (e) {
                if (!partNameSearchInput.contains(e.target) && !partSuggestionsDiv.contains(e.target)) {
                    partSuggestionsDiv.innerHTML = '';
                }
            });
            // --- End Part Name Search Logic ---


            // --- Edit Button Logic ---
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const part_id = this.dataset.part_id;
                    const part_name = this.dataset.part_name; // Get part name
                    const barcode = this.dataset.barcode;

                    // Set form action to update route
                    form.action = `/part-barcodes/${id}`;
                    formMethodInput.value = 'PUT';
                    partBarcodeIdInput.value = id;

                    // Fill the form fields
                    partNameSearchInput.value = part_name; // Set part name in search input
                    selectedPartIdInput.value = part_id; // Set hidden part_id
                    barcodeInput.value = barcode;

                    // Change button text and form title
                    formTitle.textContent = 'Edit Barcode';
                    submitButton.textContent = 'Update Barcode';
                    submitButton.classList.remove('btn-primary');
                    submitButton.classList.add('btn-success');
                    cancelButton.style.display = 'inline-block';
                });
            });

            // --- Cancel Button Logic ---
            cancelButton.addEventListener('click', function () {
                // Reset form to add new
                form.action = "{{ route('part-barcodes.store') }}";
                formMethodInput.value = 'POST';
                partBarcodeIdInput.value = '';
                selectedPartIdInput.value = ''; // Clear selected part ID
                form.reset(); // Clear all form fields, including partNameSearchInput and barcodeInput

                partSuggestionsDiv.innerHTML = ''; // Clear any open suggestions

                // Reset button text and form title
                formTitle.textContent = 'Tambah Barcode Baru';
                submitButton.textContent = 'Tambah Barcode';
                submitButton.classList.remove('btn-success');
                submitButton.classList.add('btn-primary');
                cancelButton.style.display = 'none';
            });

            // --- Barcode Table Search Realtime Logic ---
            searchInput.addEventListener('keyup', function () {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const partName = row.children[0].textContent.toLowerCase();
                    const barcode = row.children[1].textContent.toLowerCase();

                    if (partName.includes(searchTerm) || barcode.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('searchForm').addEventListener('submit', function (e) {
                e.preventDefault();
            });
            // --- End Barcode Table Search Realtime Logic ---
        });
    </script>
@endpush