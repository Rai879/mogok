{{-- resources/views/parts/index.blade.php --}}

    @extends('layouts.app')

@section('title', 'Daftar Sparepart')

@section('content')

    <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
        <div class="card-body">
            <span class="rounded">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-cogs fa-2x me-2"></i>
                    <h1 class="mb-0">Part</h1>
                </div>
                <div id="formFeedback"></div> {{-- Area untuk menampilkan feedback/error --}}

                {{-- **FORM TAMBAH / EDIT DIPINDAHKAN KE SINI (SEBELUM TABEL)** --}}
                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <h5 id="formTitle">Tambah Sparepart Baru</h5>
                    <div id="formFeedback"></div> {{-- Area untuk menampilkan feedback/error --}}
                    {{-- Penting: tambahkan enctype="multipart/form-data" untuk upload file --}}
                    <form id="partForm" enctype="multipart/form-data">
                        @csrf {{-- Token CSRF untuk keamanan --}}
                        {{-- Input tersembunyi untuk menentukan metode HTTP (POST/PUT) --}}
                        <input type="hidden" name="_method" value="POST" id="formMethod">
                        {{-- Input tersembunyi untuk menyimpan ID part yang sedang diedit --}}
                        <input type="hidden" name="edit_id" id="editId" value="">

                        <div class="row g-2">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="part_number" class="form-label">Nomor Part</label>
                                <input type="text" name="part_number" class="form-control" id="part_number" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select name="category_id" class="form-select" id="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="number" name="price" step="0.01" class="form-control" id="price" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="stock_quantity" class="form-label">Stok</label>
                                <input type="number" name="stock_quantity" class="form-control" id="stock_quantity"
                                    required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="minimum_stock" class="form-label">Minimal Stok</label>
                                <input type="number" name="minimum_stock" class="form-control" id="minimum_stock" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="condition" class="form-label">Kondisi</label>
                                <select name="condition" class="form-select" id="condition" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="new">Baru</option>
                                    <option value="used">Bekas</option>
                                    <option value="refurbished">Refurbished</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" name="brand" class="form-control" id="brand">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" id="description" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="specifications" class="form-label">Spesifikasi (JSON)</label>
                                <textarea name="specifications" class="form-control" id="specifications"
                                    rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" id="notes" rows="2"></textarea>
                            </div>

                            {{-- Input untuk Upload Gambar --}}
                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Gambar Sparepart</label>
                                <input class="form-control" type="file" id="image" name="image" accept="image/*">
                                <small class="form-text text-muted">Max 2MB, format: JPG, PNG, GIF. Gambar akan dikompres
                                    otomatis.</small>
                                <div id="imageUploadStatus" class="mt-1 text-info" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i> Mengompres gambar...
                                </div>
                                <div id="currentImagePreview" class="mt-2" style="display: none;">
                                    <p>Gambar Saat Ini:</p>
                                    <img id="imagePreviewTag" src="" alt="Current Image" class="img-thumbnail"
                                        style="max-width: 150px; height: auto; border-radius: 5px;">
                                    <button type="button" class="btn btn-sm btn-danger ms-2" id="removeImageButton">Hapus
                                        Gambar</button>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    {{-- Hidden input ini memastikan nilai 0 dikirim jika checkbox tidak dicentang --}}
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        id="is_active">
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submitButton">Tambah</button>
                                <button type="button" class="btn btn-secondary" id="cancelEditButton"
                                    style="display:none;">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="formTitle">Daftar Part</h5>
                        {{-- Form pencarian --}}
                        <form id="searchForm" class="d-flex mb-3 col-3" role="search"> {{-- Added me-auto for spacing --}}
                            <input class="form-control me-2" type="search" name="search" id="searchInput"
                                placeholder="Cari sparepart..." aria-label="Search" value="{{ request('search') }}">

                        </form>
                        {{-- Tombol ini akan mereset form untuk menambah part baru --}}

                    </div>
                    <div class="table-responsive">
                        {{-- Tabel Daftar Sparepart --}}
                        <table class="table table-bordered table-striped " id="partsTable">
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
                                    <th>Nomor Part</th>
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
                                {{-- Ini akan diisi oleh partial view parts._table_rows --}}
                                @include('partials.parts_table_rows')
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3" id="paginationLinks">
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
                                        {{-- Link Halaman Sebelumnya --}}
                                        @if ($parts->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="fas fa-chevron-left"></i>
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $parts->appends(request()->query())->previousPageUrl() }}">
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Halaman Pertama --}}
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

                                        {{-- Nomor Halaman --}}
                                        @for ($i = max(1, $parts->currentPage() - 2); $i <= min($parts->lastPage(), $parts->currentPage() + 2); $i++)
                                            @if ($i == $parts->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $i }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $parts->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                                </li>
                                            @endif
                                        @endfor

                                        {{-- Halaman Terakhir --}}
                                        @if ($parts->currentPage() < $parts->lastPage() - 2)
                                            @if ($parts->currentPage() < $parts->lastPage() - 3)
                                                <li class="page-item disabled">
                                                    <span class="page-link">...</span>
                                                </li>
                                            @endif
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $parts->appends(request()->query())->url($parts->lastPage()) }}">{{ $parts->lastPage() }}</a>
                                            </li>
                                        @endif

                                        {{-- Link Halaman Berikutnya --}}
                                        @if ($parts->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $parts->appends(request()->query())->nextPageUrl() }}">
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
            </span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            const partsTableBody = document.querySelector('#partsTable tbody');
            const paginationLinksContainer = document.getElementById('paginationLinks');

            const partForm = document.getElementById('partForm');
            const formTitle = document.getElementById('formTitle');
            const formFeedback = document.getElementById('formFeedback');
            const submitButton = document.getElementById('submitButton');
            const formMethod = document.getElementById('formMethod');
            const editId = document.getElementById('editId');
            const cancelEditButton = document.getElementById('cancelEditButton');

            // Form fields (pastikan semua ID ini ada di HTML Anda)
            const nameInput = document.getElementById('name');
            const partNumberInput = document.getElementById('part_number');
            const categoryIdSelect = document.getElementById('category_id');
            const priceInput = document.getElementById('price');
            const stockQuantityInput = document.getElementById('stock_quantity');
            const minimumStockInput = document.getElementById('minimum_stock');
            const conditionSelect = document.getElementById('condition');
            const brandInput = document.getElementById('brand');
            const descriptionTextarea = document.getElementById('description');
            const specificationsTextarea = document.getElementById('specifications');
            const notesTextarea = document.getElementById('notes');
            const isActiveCheckbox = document.getElementById('is_active');
            const imageInput = document.getElementById('image'); // Input file gambar
            const imageUploadStatus = document.getElementById('imageUploadStatus'); // Status loading kompresi
            const currentImagePreview = document.getElementById('currentImagePreview');
            const imagePreviewTag = document.getElementById('imagePreviewTag'); // Tag <img> untuk preview
            const removeImageButton = document.getElementById('removeImageButton');

            let compressedImageFile = null; // Variabel untuk menyimpan file gambar yang sudah dikompres

            // --- Fungsi Kompresi Gambar Sisi Klien ---
            function compressImage(file, maxWidth, maxHeight, quality) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = event => {
                        const img = new Image();
                        img.src = event.target.result;
                        img.onload = () => {
                            const elem = document.createElement('canvas');
                            let width = img.width;
                            let height = img.height;

                            // Resize image if it's larger than max dimensions
                            if (width > height) {
                                if (width > maxWidth) {
                                    height *= maxWidth / width;
                                    width = maxWidth;
                                }
                            } else {
                                if (height > maxHeight) {
                                    width *= maxHeight / height;
                                    height = maxHeight;
                                }
                            }

                            elem.width = width;
                            elem.height = height;
                            const ctx = elem.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);

                            // Convert canvas to blob (compressed image)
                            ctx.canvas.toBlob((blob) => {
                                if (blob) {
                                    // Create a new File object from the blob with original name and type
                                    const newFile = new File([blob], file.name, {
                                        type: file.type,
                                        lastModified: Date.now()
                                    });
                                    resolve(newFile);
                                } else {
                                    reject(new Error('Canvas to Blob conversion failed.'));
                                }
                            }, file.type, quality);
                        };
                        img.onerror = error => reject(error);
                    };
                    reader.onerror = error => reject(error);
                });
            }

            // --- Fungsi Pencarian dan Paginasi ---
            function fetchTableData(url) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Menandakan ini adalah request AJAX
                    }
                })
                    .then(res => res.text()) // Mengambil respon sebagai teks (HTML partial)
                    .then(html => {
                        partsTableBody.innerHTML = html; // Memperbarui hanya bagian tbody
                        rebindTableActions(); // Mengikat ulang event listener untuk tombol edit/hapus
                        updatePaginationLinks(url); // Memperbarui link paginasi
                    })
                    .catch(err => {
                        console.error('Error fetching table data:', err);
                        formFeedback.innerHTML = `<div class="alert alert-danger">Gagal memuat data tabel.</div>`;
                    });
            }

            function updatePaginationLinks(currentUrl) {
                const newUrl = new URL(currentUrl);
                paginationLinksContainer.querySelectorAll('.page-link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (href) {
                        const linkUrl = new URL(href);
                        // Pertahankan parameter pencarian/sortir saat navigasi halaman
                        newUrl.searchParams.forEach((value, key) => {
                            if (!linkUrl.searchParams.has(key) && key !== 'page') {
                                linkUrl.searchParams.append(key, value);
                            }
                        });
                        link.setAttribute('href', linkUrl.toString());
                    }
                });
            }

            // Event listener untuk form pencarian
            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('search', searchInput.value);
                urlParams.delete('page'); // Reset ke halaman pertama saat pencarian baru
                fetchTableData(`{{ route('parts.index') }}?${urlParams.toString()}`);
            });

            // Event listener untuk input pencarian (cari otomatis saat diketik)
            searchInput.addEventListener('input', function () {
                clearTimeout(this.delay);
                this.delay = setTimeout(() => {
                    const urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('search', this.value);
                    urlParams.delete('page');
                    fetchTableData(`{{ route('parts.index') }}?${urlParams.toString()}`);
                }, 400);
            });

            // Handle klik paginasi (delegasi event untuk link yang dimuat secara dinamis)
            paginationLinksContainer.addEventListener('click', function (e) {
                if (e.target.closest('.page-link')) {
                    e.preventDefault();
                    const link = e.target.closest('.page-link');
                    const url = link.getAttribute('href');
                    if (url) {
                        fetchTableData(url);
                    }
                }
            });


            // --- Fungsi Submit Form (Tambah/Edit) ---
            partForm.addEventListener('submit', async function (e) { // Tambahkan 'async' di sini
                e.preventDefault();
                formFeedback.innerHTML = ''; // Hapus feedback sebelumnya

                const formData = new FormData(partForm);
                let id = editId.value;
                let method = formMethod.value; // 'POST' atau 'PUT'

                // Penting: Tambahkan _method untuk request PUT agar dikenali oleh Laravel
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                // Tangani kompresi gambar sebelum mengirim
                if (imageInput.files.length > 0) {
                    imageUploadStatus.style.display = 'block'; // Tampilkan status loading
                    try {
                        // Kompres gambar: max 1200px di sisi terpanjang, kualitas 0.8 (80%)
                        compressedImageFile = await compressImage(imageInput.files[0], 1200, 1200, 0.8);
                        formData.set('image', compressedImageFile); // Ganti file asli dengan yang dikompres
                    } catch (error) {
                        console.error('Image compression failed:', error);
                        formFeedback.innerHTML = `<div class="alert alert-danger">Gagal mengompres gambar. Silakan coba lagi.</div>`;
                        imageUploadStatus.style.display = 'none';
                        return; // Hentikan proses submit jika kompresi gagal
                    } finally {
                        imageUploadStatus.style.display = 'none'; // Sembunyikan status loading
                    }
                } else if (compressedImageFile) {
                    // Jika ada gambar yang sudah dikompres dari interaksi sebelumnya tapi tidak ada file baru dipilih
                    formData.set('image', compressedImageFile);
                } else {
                    // Jika tidak ada file baru dan tidak ada compressedFile, pastikan tidak ada 'image' di formData
                    formData.delete('image');
                }

                // Tambahkan indikator untuk menghapus gambar lama jika tombol ditekan
                if (removeImageButton.dataset.shouldRemove === 'true' && id) {
                    formData.append('remove_current_image', '1');
                } else {
                    formData.delete('remove_current_image'); // Pastikan tidak ada jika tidak diperlukan
                }


                const url = id ? `/parts/${id}` : `{{ route('parts.store') }}`;

                // Metode HTTP aktual untuk request fetch selalu POST saat menggunakan _method spoofing
                const requestHttpMethod = 'POST';

                fetch(url, {
                    method: requestHttpMethod,
                    headers: {
                        'X-CSRF-TOKEN': partForm.querySelector('[name=_token]').value,
                        // Penting: Jangan set 'Content-Type' saat menggunakan FormData dengan file,
                        // browser akan otomatis mengaturnya ke multipart/form-data
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(async res => {
                        const data = await res.json(); // Selalu coba parse JSON dari respon

                        if (res.ok) { // Status kode 200-299 (sukses)
                            formFeedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                            resetForm(); // Bersihkan dan reset form
                            fetchTableData(window.location.href); // Muat ulang data tabel dan paginasi
                        } else { // Status kode 4xx, 5xx (error)
                            let errors = data.errors || {};
                            let errorMessage = '<ul>';
                            if (Object.keys(errors).length > 0) {
                                // Tampilkan error validasi spesifik
                                Object.values(errors).forEach(errArray => {
                                    errArray.forEach(err => {
                                        errorMessage += `<li>${err}</li>`;
                                    });
                                });
                            } else {
                                // Tampilkan pesan error umum dari server atau fallback
                                errorMessage += `<li>${data.message || 'Terjadi kesalahan yang tidak diketahui.'}</li>`;
                            }
                            errorMessage += '</ul>';
                            formFeedback.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
                        }
                    })
                    .catch(err => {
                        formFeedback.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.</div>`;
                        console.error('Fetch error:', err); // Log error fetch sebenarnya
                    });
            });

            // --- Fungsi Reset Form ---
            function resetForm() {
                partForm.reset();
                formTitle.textContent = 'Tambah Sparepart Baru';
                submitButton.textContent = 'Tambah';
                formMethod.value = 'POST';
                editId.value = '';
                cancelEditButton.style.display = 'none'; // Sembunyikan tombol batal
                formFeedback.innerHTML = ''; // Hapus pesan feedback
                isActiveCheckbox.checked = false; // Pastikan checkbox tidak dicentang setelah reset

                // Reset preview gambar
                currentImagePreview.style.display = 'none';
                imagePreviewTag.src = '';
                imageInput.value = ''; // Mengosongkan input file
                removeImageButton.dataset.shouldRemove = 'false'; // Reset flag penghapusan gambar
                compressedImageFile = null; // Clear compressed file
            }

            // --- Fungsi Mengisi Form untuk Edit ---
            function populateFormForEdit(partData) {
                formTitle.textContent = 'Edit Sparepart';
                submitButton.textContent = 'Update';
                formMethod.value = 'PUT';
                editId.value = partData.id;

                nameInput.value = partData.name;
                partNumberInput.value = partData.part_number || '';
                categoryIdSelect.value = partData.category_id;
                priceInput.value = partData.price;
                stockQuantityInput.value = partData.stock_quantity;
                minimumStockInput.value = partData.minimum_stock || '';
                conditionSelect.value = partData.condition || '';
                brandInput.value = partData.brand || '';
                descriptionTextarea.value = partData.description || '';
                // Tangani spesifikasi: jika itu string JSON, parse untuk ditampilkan
                try {
                    specificationsTextarea.value = partData.specifications ? JSON.stringify(JSON.parse(partData.specifications), null, 2) : '';
                } catch (e) {
                    specificationsTextarea.value = partData.specifications || ''; // Fallback jika bukan JSON valid
                }
                notesTextarea.value = partData.notes || '';
                isActiveCheckbox.checked = partData.is_active == 1; // Centang jika aktif

                // Tampilkan gambar saat ini jika ada
                if (partData.image && partData.image !== 'null' && partData.image !== '') { // Periksa juga string 'null' dan string kosong
                    imagePreviewTag.src = `/storage/${partData.image}`; // Asumsi gambar di public/storage
                    currentImagePreview.style.display = 'block';
                } else {
                    currentImagePreview.style.display = 'none';
                    imagePreviewTag.src = '';
                }
                imageInput.value = ''; // Kosongkan input file agar user bisa memilih file baru
                removeImageButton.dataset.shouldRemove = 'false'; // Reset flag penghapusan gambar
                compressedImageFile = null; // Clear compressed file

                cancelEditButton.style.display = 'inline-block'; // Tampilkan tombol batal
                formFeedback.innerHTML = ''; // Hapus pesan feedback
            }

            // --- Fungsi Mengikat Ulang Tombol Edit dan Hapus (setelah refresh tabel AJAX) ---
            function rebindTableActions() {
                // Tombol Edit
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.removeEventListener('click', handleEditClick); // Cegah listener duplikat
                    btn.addEventListener('click', handleEditClick);
                });

                // Tombol Hapus
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.removeEventListener('click', handleDeleteClick); // Cegah listener duplikat
                    btn.addEventListener('click', handleDeleteClick);
                });
            }

            function handleEditClick() {
                const partData = this.dataset; // Semua atribut data- ada di dataset
                populateFormForEdit(partData);
                // Gulir ke form
                partForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            async function handleDeleteClick(e) {
                e.preventDefault();
                const partId = this.dataset.id;
                const partName = this.dataset.name; // Ambil nama untuk konfirmasi

                // Dialog konfirmasi kustom (ganti dengan modal jika Anda mau)
                if (!confirm(`Yakin ingin menghapus sparepart "${partName}"?`)) {
                    return; // Pengguna membatalkan
                }

                try {
                    const response = await fetch(`/parts/${partId}`, {
                        method: 'POST', // Laravel menggunakan POST dengan _method untuk DELETE
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded', // Diperlukan untuk _method
                        },
                        body: new URLSearchParams({
                            _method: 'DELETE'
                        })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        formFeedback.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        fetchTableData(window.location.href); // Refresh tabel
                    } else {
                        let errors = data.errors || {};
                        let errorMessage = '<ul>';
                        if (Object.keys(errors).length > 0) {
                            Object.values(errors).forEach(errArray => {
                                errArray.forEach(err => {
                                    errorMessage += `<li>${err}</li>`;
                                });
                            });
                        } else {
                            errorMessage += `<li>${data.message || 'Terjadi kesalahan saat menghapus sparepart.'}</li>`;
                        }
                        errorMessage += '</ul>';
                        formFeedback.innerHTML = `<div class="alert alert-danger">${errorMessage}</div>`;
                    }
                } catch (error) {
                    formFeedback.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan jaringan atau server saat menghapus.</div>`;
                    console.error('Delete error:', error);
                }
            }

            // --- Event Listener untuk Tombol-tombol ---
            cancelEditButton.addEventListener('click', resetForm);

            // Event listener untuk tombol "Hapus Gambar"
            removeImageButton.addEventListener('click', function () {
                currentImagePreview.style.display = 'none';
                imagePreviewTag.src = '';
                imageInput.value = ''; // Clear the file input
                removeImageButton.dataset.shouldRemove = 'true'; // Set a flag to tell the backend to remove the image
                compressedImageFile = null; // Clear compressed file
            });

            // Pengikatan awal saat halaman dimuat
            rebindTableActions();
        });
    </script>
@endsection