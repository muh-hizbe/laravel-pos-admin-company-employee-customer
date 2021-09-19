<?php
namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRepository {
    protected $model = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getAllProduct($relation = null, $filterByCompany = false)
    {
        if ($filterByCompany) {
            $companyId = Auth::user()->hasRole('Employee') ? Auth::user()->profile->company_id : Auth::user()->id;
            $products = ($relation != null) ? Product::with($relation)->withCount('transaction')->where('company_id', $companyId)->get() : Product::withCount('transaction')->where('company_id', $companyId)->get();
        } else {
            $products = ($relation != null) ? Product::with($relation)->withCount('transaction')->get() : Product::withCount('transaction')->get();
        }
        return $products;
    }

    public function storeProduct($request)
    {
        $companyId = Auth::user()->profile->company_id;
        DB::beginTransaction();
        try {
            Product::create([
                "category_id" => $request['category_id'],
                "company_id" => $companyId,
                "name" => $request['name'],
                "price" => $request['price'],
                "discount_price" => $request['discount_price'],
                "stock" => $request['stock'],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
        DB::commit();

        return true;
    }

    public function getProductById($id, $relation = null)
    {
        $product = ($relation != null) ? Product::where('id', $id)->with($relation)->first() : Product::where('id', $id)->first();
        return $product;
    }

    public function updateProduct($id, $request)
    {
        try {
            $product = Product::where('id', $id)->first();
            $product->name = $request['name'];
            $product->category_id = $request['category_id'];
            $product->price = $request['price'];
            $product->discount_price = $request['discount_price'];
            $product->stock = $request['stock'];
            $product->save();
        } catch (QueryException $th) {
            return false;
        }

        return true;
    }

    public function deleteProductById($id)
    {
        try {
            $user = Product::find($id)->delete();
        } catch (QueryException $th) {
            return $th;
        }

        return true;
    }
}
