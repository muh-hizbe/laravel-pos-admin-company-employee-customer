<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use App\Traits\ApiResponser;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    use ApiResponser;

    public function index(CategoryRepository $repository)
    {
        $categories = $repository->getAllCategory();
        return view('category.index', compact(['categories']));
    }

    public function count(CategoryRepository $repository)
    {
        $count = $repository->getAllCategory()->count();
        return response()->json($count);
    }

    public function store(CategoryStoreRequest $request, CategoryRepository $repository)
    {
        $validated = $request->validated();

        try {
            $category = $repository->storeCategory($validated);
            if (!is_bool($category)) {
                return $this->error("Category failed to store.", 500, [
                    "Error" => $category
                ]);
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500);
        }

        return response()->json([
            "message" => "Category was created successfully"
        ], 201);
    }

    public function updateCategory(CategoryStoreRequest $request, CategoryRepository $repository, $id)
    {
        $validated = $request->validated();

        try {
            $category = $repository->updateCategory($id, $validated);
            if ($category != true) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Category with id = ".$id." cannot be updated.", 500, $th);
        }

        return response()->json([
            "message" => "Category was updated successfully"
        ], 201);
    }

    public function dataTable(CategoryRepository $repository)
    {
        $categories = $repository->getAllCategory(null, true);
        // return response()->json($categories);
        return DataTables::of($categories)
        ->addIndexColumn()
        ->removeColumn('created_at')
        ->removeColumn('updated_at')
        ->addColumn('action', function($categories) {
            $button = '';

            $button .= '<button id="edit-category" class="px-3 py-1 rounded-full border border-yellow-500 mr-2" onclick="editCategory('.$categories->id.')">Edit</button>';
            $button .= '<button id="delete-category" class="px-3 py-1 rounded-full border border-green-500" onclick="deleteCategory('.$categories->id.')">Delete</button>';

            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function dataJson(CategoryRepository $repository)
    {
        $categories = $repository->getAllCategory(null, true);
        return response()->json(CategoryResource::collection($categories));
    }

    public function getById($id, CategoryRepository $repository)
    {
        try {
            $category = $repository->getCategoryById($id);
            if (empty($category)) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Category with id = ".$id." not found.", 404);
        }
        return $this->success($category);
    }

    public function destroy($id, CategoryRepository $repository)
    {
        try {
            $category = $repository->deleteCategoryById($id);
            if (!is_bool($category)) {
                return $this->error($category, 500, "Cannot delete Category with id = ".$id.".");
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500, "Cannot delete Category with id = ".$id.".");
        }
        return $this->success("Category was deleted successfully");
    }
}
