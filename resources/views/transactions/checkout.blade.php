@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Checkout / Point of Sale</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Add Part by Barcode</h3>
            <form id="barcodeForm" action="{{ route('transactions.add_to_cart') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Scan or type barcode" id="barcodeInput" name="barcode" autofocus>
                    <button class="btn btn-outline-secondary" type="submit">Add by Barcode</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <h3>Search and Add Part</h3>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search part name or part number" id="partSearchInput">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
            </div>
            <div id="searchResults" class="list-group">
                </div>
        </div>
    </div>

    <hr>

    <h3>Current Cart Items</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Part Name</th>
                <th>Part Number</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="cartItems">
            @forelse ($tempTransactions as $item)
                <tr>
                    <td>{{ $item->part->name }}</td>
                    <td>{{ $item->part->part_number }}</td>
                    <td>Rp {{ number_format($item->price_at_transaction, 2) }}</td>
                    <td>
                        <form action="{{ route('transactions.update_cart_quantity', $item) }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->part->stock_quantity }}" class="form-control form-control-sm me-2" style="width: 80px;">
                            <button type="submit" class="btn btn-sm btn-info">Update</button>
                        </form>
                    </td>
                    <td>Rp {{ number_format($item->quantity * $item->price_at_transaction, 2) }}</td>
                    <td>
                        <form action="{{ route('transactions.remove_from_cart', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?')">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No items in cart.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6 offset-md-6">
            <div class="card p-3">
                <h4>Total Amount: <span id="totalAmount">Rp {{ number_format($totalAmount, 2) }}</span></h4>
                <form action="{{ route('transactions.process') }}" method="POST" id="transactionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="cashPaid" class="form-label">Cash Paid (Rp)</label>
                        <input type="number" step="0.01" class="form-control" id="cashPaid" name="cash_paid" required min="{{ $totalAmount }}">
                    </div>
                    <div class="mb-3">
                        <label for="changeDue" class="form-label">Change Due (Rp)</label>
                        <input type="text" class="form-control" id="changeDue" readonly value="0.00">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100" id="processTransactionBtn">Process Transaction</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const partSearchInput = document.getElementById('partSearchInput');
        const searchButton = document.getElementById('searchButton');
        const searchResultsDiv = document.getElementById('searchResults');
        const barcodeInput = document.getElementById('barcodeInput');
        const totalAmountSpan = document.getElementById('totalAmount');
        const cashPaidInput = document.getElementById('cashPaid');
        const changeDueInput = document.getElementById('changeDue');
        const transactionForm = document.getElementById('transactionForm');

        // Function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2
            }).format(amount);
        }

        // Auto-calculate change
        cashPaidInput.addEventListener('input', function() {
            let total = parseFloat("{{ $totalAmount }}");
            let cashPaid = parseFloat(this.value);
            if (isNaN(cashPaid)) {
                cashPaid = 0;
            }
            let change = cashPaid - total;
            changeDueInput.value = formatCurrency(change);
        });

        // Part Search
        searchButton.addEventListener('click', function() {
            const query = partSearchInput.value;
            if (query.length < 2) {
                searchResultsDiv.innerHTML = '<div class="list-group-item">Please enter at least 2 characters.</div>';
                return;
            }

            fetch(`/api/parts/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    searchResultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(part => {
                            const item = document.createElement('a');
                            item.href = '#';
                            item.classList.add('list-group-item', 'list-group-item-action');
                            item.innerHTML = `
                                <strong>${part.name}</strong> (${part.part_number}) -
                                Price: ${formatCurrency(part.price)} -
                                Stock: ${part.stock_quantity}
                            `;
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                // Add to cart via form submission
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = "{{ route('transactions.add_to_cart') }}";
                                form.style.display = 'none';

                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = csrfToken;
                                form.appendChild(csrfInput);

                                const partIdInput = document.createElement('input');
                                partIdInput.type = 'hidden';
                                partIdInput.name = 'part_id';
                                partIdInput.value = part.id;
                                form.appendChild(partIdInput);

                                document.body.appendChild(form);
                                form.submit();
                            });
                            searchResultsDiv.appendChild(item);
                        });
                    } else {
                        searchResultsDiv.innerHTML = '<div class="list-group-item">No parts found.</div>';
                    }
                })
                .catch(error => console.error('Error searching parts:', error));
        });

        // Barcode input auto-submit (on Enter key)
        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent default form submission
                if (barcodeInput.value.trim() !== '') {
                    document.getElementById('barcodeForm').submit();
                }
            }
        });

        // Set initial change due if cash paid has a value on page load (e.g., after validation error)
        if (cashPaidInput.value) {
             let total = parseFloat("{{ $totalAmount }}");
             let cashPaid = parseFloat(cashPaidInput.value);
             changeDueInput.value = formatCurrency(cashPaid - total);
        }
    });
</script>
@endpush
@endsection