@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Transaction Details - Invoice: {{ $transaction->invoice_number }}</h1>

    <div class="card mb-4">
        <div class="card-header">
            Transaction Summary
        </div>
        <div class="card-body">
            <p><strong>Total Amount:</strong> Rp {{ number_format($transaction->total_amount, 2) }}</p>
            <p><strong>Cash Paid:</strong> Rp {{ number_format($transaction->cash_paid, 2) }}</p>
            <p><strong>Change Due:</strong> Rp {{ number_format($transaction->change_due, 2) }}</p>
            <p><strong>Transaction Date:</strong> {{ $transaction->created_at->format('d M Y H:i:s') }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Items in this Transaction
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Part Name</th>
                        <th>Part Number</th>
                        <th>Quantity</th>
                        <th>Price at Transaction</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $detail)
                        <tr>
                            <td>{{ $detail->part->name }}</td>
                            <td>{{ $detail->part->part_number }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->price_at_transaction, 2) }}</td>
                            <td>Rp {{ number_format($detail->quantity * $detail->price_at_transaction, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a href="{{ route('transactions.history') }}" class="btn btn-secondary mt-3">Back to History</a>
</div>
@endsection