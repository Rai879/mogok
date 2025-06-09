<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\TempTransaction;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function getTempTransactions()
    {
        $tempTransactions = TempTransaction::with('part')->get();
        $totalAmount = $tempTransactions->sum(function($item) {
            return $item->quantity * $item->price_at_transaction;
        });
        return response()->json([
            'items' => $tempTransactions,
            'total_amount' => $totalAmount
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:parts,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $part = Part::find($request->part_id);

        if (!$part) {
            return response()->json(['message' => 'Part not found.'], 404);
        }

        $quantityToAdd = $request->quantity ?? 1;

        if ($part->stock_quantity < $quantityToAdd) {
            return response()->json(['message' => 'Insufficient stock for ' . $part->name . '. Available: ' . $part->stock_quantity], 400);
        }

        $tempTransaction = TempTransaction::where('part_id', $part->id)->first();

        if ($tempTransaction) {
            if (($tempTransaction->quantity + $quantityToAdd) > $part->stock_quantity) {
                return response()->json(['message' => 'Cannot add more. Exceeds available stock for ' . $part->name . '.'], 400);
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

        return response()->json(['message' => 'Part added to cart.', 'cart_item' => $tempTransaction ?? TempTransaction::where('part_id', $part->id)->first()], 200);
    }

    public function updateCartQuantity(Request $request, $tempTransactionId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $tempTransaction = TempTransaction::find($tempTransactionId);

        if (!$tempTransaction) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }

        if ($request->quantity > $tempTransaction->part->stock_quantity) {
            return response()->json(['message' => 'Cannot set quantity to ' . $request->quantity . '. Only ' . $tempTransaction->part->stock_quantity . ' available for ' . $tempTransaction->part->name], 400);
        }

        $tempTransaction->quantity = $request->quantity;
        $tempTransaction->save();

        return response()->json(['message' => 'Quantity updated.', 'cart_item' => $tempTransaction], 200);
    }

    public function removeFromCart($tempTransactionId)
    {
        $tempTransaction = TempTransaction::find($tempTransactionId);
        if (!$tempTransaction) {
            return response()->json(['message' => 'Cart item not found.'], 404);
        }
        $tempTransaction->delete();
        return response()->json(['message' => 'Part removed from cart.'], 200);
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'cash_paid' => 'required|numeric|min:0',
        ]);

        $tempTransactions = TempTransaction::with('part')->get();

        if ($tempTransactions->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty. Please add items before processing.'], 400);
        }

        $totalAmount = $tempTransactions->sum(function($item) {
            return $item->quantity * $item->price_at_transaction;
        });

        if ($request->cash_paid < $totalAmount) {
            return response()->json(['message' => 'Cash paid is less than the total amount.'], 400);
        }

        $changeDue = $request->cash_paid - $totalAmount;

        try {
            DB::transaction(function () use ($tempTransactions, $totalAmount, $request, $changeDue) {
                $transaction = Transaction::create([
                    'invoice_number' => 'INV-' . time() . '-' . Str::random(5),
                    'total_amount' => $totalAmount,
                    'cash_paid' => $request->cash_paid,
                    'change_due' => $changeDue,
                ]);

                foreach ($tempTransactions as $tempItem) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'part_id' => $tempItem->part_id,
                        'quantity' => $tempItem->quantity,
                        'price_at_transaction' => $tempItem->price_at_transaction,
                    ]);

                    $part = Part::find($tempItem->part_id);
                    if ($part) {
                        $part->stock_quantity -= $tempItem->quantity;
                        $part->save();
                    }
                    $tempItem->delete();
                }
            });
        } catch (\Exception $e) {
            return response()->json(['message' => 'Transaction failed: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Transaction completed successfully!', 'change_due' => $changeDue], 200);
    }

    public function getHistory()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($transactions);
    }

    public function show($transactionId)
    {
        $transaction = Transaction::with('details.part')->find($transactionId);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }
        return response()->json($transaction);
    }
}