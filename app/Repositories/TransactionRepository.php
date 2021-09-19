<?php
namespace App\Repositories;

use App\Jobs\SendEmail;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class TransactionRepository {
    protected $model = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getAllTransaction($relation = null, $filterByCompany = false)
    {
        if ($filterByCompany) {
            $companyId = Auth::user()->hasAnyRole('Employee') ?
                        Auth::user()->profile->company_id : Auth::user()->id;

            $transactions = ($relation != null) ?
                            Transaction::with($relation)
                            ->whereHas('product', function($query) use($companyId) {
                                $query->where('company_id', $companyId);
                            })->get() :
                            Transaction::withCount('transaction')
                            ->whereHas('product', function($query) use($companyId) {
                                $query->where('company_id', $companyId);
                            })->get();
        } else {
            $transactions = ($relation != null) ?
                            Transaction::with($relation)->get() :
                            Transaction::all();
        }
        return $transactions;
    }

    public function storeTransaction($request)
    {
        // $companyId = Auth::user()->profile->company_id;
        $customerId = Auth::user()->id;
        $customerName = Auth::user()->profile->fullName;
        $customerEmail = Auth::user()->email;
        $details = [
            'email' => $customerEmail,
            'name' => $customerName,
            'product' => $request['name']
        ];
        try {
            $transaction = Transaction::create([
                "product_id" => $request['id'],
                "customer_id" => $customerId,
                "price" => $request['price'],
                "quantity" => 1,
                "discount_price" => $request['discount_price']
            ]);

            $details["product_id"] = $transaction->product_id;
            $details["customer_id"] = $transaction->customer_id;
            $details["price"] = $transaction->price;
            $details["quantity"] = $transaction->quantity;
            $details["discount_price"] = $transaction->discount_price;
            // new SendEmail();
            dispatch(new SendEmail($details));
            // Mail::to('hizbe@example.com')->send(new EmailForInvoice());
        } catch (QueryException $th) {
            return $th;
        }

        return true;
    }

    public function getTransactionById($id, $relation = null)
    {
        $transaction = ($relation != null) ? Transaction::where('id', $id)->with($relation)->first() : Transaction::where('id', $id)->first();
        return $transaction;
    }

    public function updateTransaction($id, $request)
    {
        try {
            $transaction = Transaction::where('id', $id)->first();
            $transaction->name = $request['name'];
            $transaction->category_id = $request['category_id'];
            $transaction->price = $request['price'];
            $transaction->discount_price = $request['discount_price'];
            $transaction->stock = $request['stock'];
            $transaction->save();
        } catch (QueryException $th) {
            return false;
        }

        return true;
    }

    public function deleteTransactionById($id)
    {
        try {
            $user = Transaction::find($id)->delete();
        } catch (QueryException $th) {
            return $th;
        }

        return true;
    }
}
