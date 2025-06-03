@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Transaction History</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
        <tbody>
            @forelse ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>Rp {{ number_format($transaction->total_amount, 2) }}</td>
                    <td>Rp {{ number_format($transaction->cash_paid, 2) }}</td>
                    <td>Rp {{ number_format($transaction->change_due, 2) }}</td>
                    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-info">View Details</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No transactions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $transactions->links() }}
</div>
@endsection