@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" style="border: 1px solid #dee2e6; border-radius: 8px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center mb-4">
                        <i class="fas fa-history fa-2x me-2"></i>
                        <h1>Transaction History</h1>
                    </div>
                    <form id="searchForm" method="GET" class="d-flex"> {{-- Tambahkan ID untuk form --}}
                        <input type="text" name="search" id="searchInput" class="form-control me-2" placeholder="Cari transaksi..."
                            value="{{ request('search') }}">
                    </form>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Invoice Number</th>
                            <th>Total Amount</th>
                            <th>Cash Paid</th>
                            <th>Change Due</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="transactionTableBody"> {{-- Tambahkan ID untuk body tabel --}}
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->invoice_number }}</td>
                                <td>Rp {{ number_format($transaction->total_amount, 2) }}</td>
                                <td>Rp {{ number_format($transaction->cash_paid, 2) }}</td>
                                <td>Rp {{ number_format($transaction->change_due, 2) }}</td>
                                <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-info">View
                                        Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Custom Paginasi di sini --}}
                <div class="d-flex justify-content-between align-items-center mt-3" id="paginationLinks">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $transactions->firstItem() ?? 0 }} hingga {{ $transactions->lastItem() ?? 0 }}
                            dari {{ $transactions->total() }} hasil
                        </small>
                    </div>
                    <div>
                        @if ($transactions->hasPages())
                            <nav aria-label="Pagination">
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Link Halaman Sebelumnya --}}
                                    @if ($transactions->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $transactions->appends(request()->query())->previousPageUrl() }}">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Halaman Pertama --}}
                                    @if ($transactions->currentPage() > 3)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $transactions->appends(request()->query())->url(1) }}">1</a>
                                        </li>
                                        @if ($transactions->currentPage() > 4)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif

                                    {{-- Nomor Halaman --}}
                                    @for ($i = max(1, $transactions->currentPage() - 2); $i <= min($transactions->lastPage(), $transactions->currentPage() + 2); $i++)
                                        @if ($i == $transactions->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $i }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $transactions->appends(request()->query())->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endif
                                    @endfor

                                    {{-- Halaman Terakhir --}}
                                    @if ($transactions->currentPage() < $transactions->lastPage() - 2)
                                        @if ($transactions->currentPage() < $transactions->lastPage() - 3)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $transactions->appends(request()->query())->url($transactions->lastPage()) }}">{{ $transactions->lastPage() }}</a>
                                        </li>
                                    @endif

                                    {{-- Link Halaman Berikutnya --}}
                                    @if ($transactions->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $transactions->appends(request()->query())->nextPageUrl() }}">
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const transactionTableBody = document.getElementById('transactionTableBody');
        // Mendapatkan semua baris tabel saat DOM dimuat
        const tableRows = transactionTableBody.querySelectorAll('tr');

        // Event listener untuk input pencarian
        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase(); // Ambil teks pencarian dan ubah ke huruf kecil

            tableRows.forEach(row => {
                // Ambil teks dari setiap sel yang ingin Anda cari
                const invoiceNumber = row.children[0].textContent.toLowerCase();
                const totalAmount = row.children[1].textContent.toLowerCase();
                const cashPaid = row.children[2].textContent.toLowerCase();
                const changeDue = row.children[3].textContent.toLowerCase();
                const date = row.children[4].textContent.toLowerCase(); // Tanggal

                // Periksa apakah teks pencarian cocok dengan salah satu kolom
                if (invoiceNumber.includes(searchTerm) ||
                    totalAmount.includes(searchTerm) ||
                    cashPaid.includes(searchTerm) ||
                    changeDue.includes(searchTerm) ||
                    date.includes(searchTerm)
                ) {
                    row.style.display = ''; // Tampilkan baris jika ada kecocokan
                } else {
                    row.style.display = 'none'; // Sembunyikan baris jika tidak ada kecocokan
                }
            });
        });

        // Mencegah form pencarian di-submit saat tombol Enter ditekan
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
        });
    });
</script>
@endpush
