<?php
namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryRepository {
    protected $model = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getAllCategory($relation = null, $filterByCompany = false)
    {
        if ($filterByCompany) {
            $companyId = Auth::user()->hasRole('Employee') ? Auth::user()->profile->company_id : Auth::user()->id;
            $categories = ($relation != null) ? Category::with($relation)->withCount('products')->where('company_id', $companyId)->get() : Category::withCount('products')->where('company_id', $companyId)->get();
        } else {
            $categories = ($relation != null) ? Category::with($relation)->withCount('products')->get() : Category::withCount('products')->get();
        }
        return $categories;
    }

    public function storeCategory($request)
    {
        $companyId = Auth::user()->profile->company_id;
        DB::beginTransaction();
        try {
            Category::create([
                "name" => $request['name'],
                "company_id" => $companyId,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
        DB::commit();

        return true;
    }

    public function getCategoryById($id, $relation = null)
    {
        $category = ($relation != null) ? Category::where('id', $id)->with($relation)->first() : Category::where('id', $id)->first();
        return $category;
    }

    public function updateCategory($id, $request)
    {
        try {
            $category = Category::where('id', $id)->first();
            $category->name = $request['name'];
            $category->save();
        } catch (QueryException $th) {
            return false;
        }

        return true;
    }

    public function deleteCategoryById($id)
    {
        try {
            $user = Category::find($id)->delete();
        } catch (QueryException $th) {
            return $th;
        }

        return true;
    }
}
