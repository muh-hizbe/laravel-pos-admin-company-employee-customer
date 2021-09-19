<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Repositories\ProductRepository;
use App\Traits\ApiResponser;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    use ApiResponser;

    public function index(ProductRepository $repository)
    {
        $products = $repository->getAllProduct();
        return view('product.index', compact(['products']));
    }

    public function count(ProductRepository $repository)
    {
        $count = $repository->getAllProduct()->count();
        return response()->json($count);
    }

    public function store(ProductStoreRequest $request, ProductRepository $repository)
    {
        $validated = $request->validated();

        try {
            $product = $repository->storeProduct($validated);
            if (!is_bool($product)) {
                return $this->error($product, 500);
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500);
        }

        return response()->json([
            "message" => "Product was created successfully"
        ], 201);
    }

    public function updateProduct(ProductStoreRequest $request, ProductRepository $repository, $id)
    {
        $validated = $request->validated();

        try {
            $product = $repository->updateProduct($id, $validated);
            if ($product != true) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Product with id = ".$id." cannot be updated.", 500, $th);
        }

        return response()->json([
            "message" => "Product was updated successfully"
        ], 201);
    }

    public function dataTable(ProductRepository $repository)
    {
        $products = $repository->getAllProduct(null, true);
        // return response()->json($products);
        return DataTables::of($products)
        ->addIndexColumn()
        ->removeColumn('created_at')
        ->removeColumn('updated_at')
        ->removeColumn('category_id')
        ->removeColumn('company_id')
        ->editColumn('price', function($products) {
            return 'Rp. ' . number_format($products->price, 2, ',', '.');
        })
        ->editColumn('discount_price', function($products) {
            return !empty($products->discount_price) ? 'Rp. ' . number_format($products->discount_price, 2, ',', '.') : '-';
        })
        ->addColumn('category', function($products) {
            return $products->category->name;
        })
        ->addColumn('action', function($products) {
            $button = '';

            $button .= '<button id="edit-product" class="px-3 py-1 rounded-full border border-yellow-500 mr-2" onclick="editProduct('.$products->id.')">Edit</button>';
            $button .= '<button id="delete-product" class="px-3 py-1 rounded-full border border-green-500" onclick="deleteProduct('.$products->id.')">Delete</button>';

            return $button;
        })
        ->rawColumns(['category', 'action'])
        ->make(true);
    }

    public function getById($id, ProductRepository $repository)
    {
        try {
            $product = $repository->getProductById($id);
            if (empty($product)) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Product with id = ".$id." not found.", 404);
        }
        return $this->success($product);
    }

    public function destroy($id, ProductRepository $repository)
    {
        try {
            $product = $repository->deleteProductById($id);
            if (!is_bool($product)) {
                return $this->error($product, 500, "Cannot delete Product with id = ".$id.".");
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500, "Cannot delete Product with id = ".$id.".");
        }
        return $this->success("Product was deleted successfully");
    }
}
