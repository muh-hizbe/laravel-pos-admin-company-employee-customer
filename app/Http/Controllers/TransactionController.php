<?php

namespace App\Http\Controllers;

use App\Repositories\TransactionRepository;
use App\Traits\ApiResponser;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    use ApiResponser;

    public function index(TransactionRepository $repository)
    {
        $transactions = $repository->getAllTransaction('product', true);
        return view('transaction.index', compact(['transactions']));
    }

    public function count(TransactionRepository $repository)
    {
        $count = $repository->getAllTransaction()->count();
        return response()->json($count);
    }

    public function store(TransactionStoreRequest $request, TransactionRepository $repository)
    {
        $validated = $request->validated();

        try {
            $transaction = $repository->storeTransaction($validated);
            if (!is_bool($transaction)) {
                return $this->error($transaction, 500);
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500);
        }

        return response()->json([
            "message" => "Transaction was created successfully"
        ], 201);
    }

    public function updateTransaction(TransactionStoreRequest $request, TransactionRepository $repository, $id)
    {
        $validated = $request->validated();

        try {
            $transaction = $repository->updateTransaction($id, $validated);
            if ($transaction != true) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Transaction with id = ".$id." cannot be updated.", 500, $th);
        }

        return response()->json([
            "message" => "Transaction was updated successfully"
        ], 201);
    }

    public function dataTable(TransactionRepository $repository)
    {
        $transactions = $repository->getAllTransaction(['product', 'customer'], true);
        // return response()->json($transactions);
        return DataTables::of($transactions)
        ->addIndexColumn()
        ->removeColumn('updated_at')
        ->removeColumn('product_id')
        ->removeColumn('customer_id')
        ->removeColumn('invoice_id')
        ->removeColumn('product')
        ->removeColumn('customer')
        ->editColumn('price', function($transactions) {
            return 'Rp. ' . number_format($transactions->price, 0, ',', '.');
        })
        ->editColumn('discount_price', function($transactions) {
            return !empty($transactions->discount_price) ? 'Rp. ' . number_format($transactions->discount_price, 0, ',', '.') : '-';
        })
        ->addColumn('quantity', function($transactions) {
            return $transactions->quantity;
        })
        ->addColumn('product_name', function($transactions) {
            return $transactions->product->name;
        })
        ->addColumn('customer_name', function($transactions) {
            return $transactions->customer->profile->fullName;
        })
        ->addColumn('customer_phone', function($transactions) {
            return $transactions->customer->profile->phone_number;
        })
        ->addColumn('total', function($transactions) {
            $total = !empty($transactions->discount_price) ?
                    $transactions->quantity*$transactions->discount_price :
                    $transactions->quantity*$transactions->price;

            return 'Rp. ' . number_format($total, 0, ',', '.');
        })
        ->editColumn('created_at', function($transactions) {
                return date('Y/m/d H:i:s (e)', strtotime($transactions->updated_at));
            })
        ->make(true);
    }

    public function getById($id, TransactionRepository $repository)
    {
        try {
            $transaction = $repository->getTransactionById($id);
            if (empty($transaction)) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Transaction with id = ".$id." not found.", 404);
        }
        return $this->success($transaction);
    }

    public function destroy($id, TransactionRepository $repository)
    {
        try {
            $transaction = $repository->deleteTransactionById($id);
            if (!is_bool($transaction)) {
                return $this->error($transaction, 500, "Cannot delete Transaction with id = ".$id.".");
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500, "Cannot delete Transaction with id = ".$id.".");
        }
        return $this->success("Transaction was deleted successfully");
    }
}
