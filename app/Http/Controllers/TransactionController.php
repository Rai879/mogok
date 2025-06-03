<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartBarcode;
use App\Models\TempTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function checkout()
    {
        $tempTransactions = TempTransaction::with('part')->get();
        $totalAmount = $tempTransactions->sum(function($item) {
            return $item->quantity * $item->price_at_transaction;
        });
        return view('transactions.checkout', compact('tempTransactions', 'totalAmount'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'part_id' => 'sometimes|required_without:barcode|exists:parts,id',
            'barcode' => 'sometimes|required_without:part_id|exists:part_barcodes,barcode',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $part = null;
        if ($request->has('part_id')) {
            $part = Part::find($request->part_id);
        } elseif ($request->has('barcode')) {
            $partBarcode = PartBarcode::where('barcode', $request->barcode)->first();
            if ($partBarcode) {
                $part = $partBarcode->part;
            }
        }

        if (!$part) {
            return redirect()->back()->with('error', 'Part not found.');
        }

        if ($part->stock_quantity < ($request->quantity ?? 1)) {
            return redirect()->back()->with('error', 'Insufficient stock for ' . $part->name . '. Available: ' . $part->stock_quantity);
        }

        $quantityToAdd = $request->quantity ?? 1;

        $tempTransaction = TempTransaction::where('part_id', $part->id)->first();

        if ($tempTransaction) {
            // Check if adding more would exceed stock
            if (($tempTransaction->quantity + $quantityToAdd) > $part->stock_quantity) {
                 return redirect()->back()->with('error', 'Cannot add more. Exceeds available stock for ' . $part->name . '.');
            }
            $tempTransaction->quantity += $quantityToAdd;
            $tempTransaction->save();
        } else {
            TempTransaction::create([
                'part_id' => $part->id,
                'quantity' => $quantityToAdd,
                'price_at_transaction' => $part->price,
            ]);
        }

        return redirect()->route('transactions.checkout')->with('success', 'Part added to cart.');
    }

    public function updateCartQuantity(Request $request, TempTransaction $tempTransaction)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->quantity > $tempTransaction->part->stock_quantity) {
            return redirect()->back()->with('error', 'Cannot set quantity to ' . $request->quantity . '. Only ' . $tempTransaction->part->stock_quantity . ' available for ' . $tempTransaction->part->name);
        }

        $tempTransaction->quantity = $request->quantity;
        $tempTransaction->save();

        return redirect()->route('transactions.checkout')->with('success', 'Quantity updated.');
    }

    public function removeFromCart(TempTransaction $tempTransaction)
    {
        $tempTransaction->delete();
        return redirect()->route('transactions.checkout')->with('success', 'Part removed from cart.');
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'cash_paid' => 'required|numeric|min:0',
        ]);

        $tempTransactions = TempTransaction::with('part')->get();

        if ($tempTransactions->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty. Please add items before processing.');
        }

        $totalAmount = $tempTransactions->sum(function($item) {
            return $item->quantity * $item->price_at_transaction;
        });

        if ($request->cash_paid < $totalAmount) {
            return redirect()->back()->with('error', 'Cash paid is less than the total amount.');
        }

        $changeDue = $request->cash_paid - $totalAmount;

        DB::transaction(function () use ($tempTransactions, $totalAmount, $request, $changeDue) {
            // Create a new transaction
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . time() . '-' . Str::random(5),
                'total_amount' => $totalAmount,
                'cash_paid' => $request->cash_paid,
                'change_due' => $changeDue,
            ]);

            foreach ($tempTransactions as $tempItem) {
                // Move item to transaction details
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'part_id' => $tempItem->part_id,
                    'quantity' => $tempItem->quantity,
                    'price_at_transaction' => $tempItem->price_at_transaction,
                ]);

                // Update stock quantity
                $part = Part::find($tempItem->part_id);
                if ($part) {
                    $part->stock_quantity -= $tempItem->quantity;
                    $part->save();
                }

                // Delete from temporary transactions
                $tempItem->delete();
            }
        });

        return redirect()->route('transactions.history')->with('success', 'Transaction completed successfully! Change: Rp ' . number_format($changeDue, 2));
    }

    public function history()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate(10);
        return view('transactions.history', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('details.part');
        return view('transactions.show', compact('transaction'));
    }
}