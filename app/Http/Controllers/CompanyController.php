<?php

namespace App\Http\Controllers;

use App\Http\Requests\{
    UserRegisterRequest,
    UserUpdateRequest
};
use App\Repositories\{
    ProfileRepository,
    UserRepository
};
use App\Traits\ApiResponser;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CompanyController extends Controller
{
    use ApiResponser;

    public function index(UserRepository $repository)
    {
        $companies = $repository->getAllCompany();
        return view('company.index', compact(['companies']));
    }

    public function count(UserRepository $repository)
    {
        $count = $repository->getAllCompany()->count();
        return response()->json($count);
    }

    public function store(UserRegisterRequest $request, UserRepository $repository)
    {
        $validated = $request->validated();

        try {
            $company = $repository->storeCompany($validated);
            if ($company === false) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500);
        }

        return response()->json([
            "message" => "company was created successfully"
        ], 201);
    }

    public function updateProfile(UserUpdateRequest $request, ProfileRepository $repository, $id)
    {
        $validated = $request->validated();

        try {
            $company = $repository->updateUserProfile($id, $validated); // diganti company nanti
            if ($company != true) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Company profile with id = ".$id." cannot be updated.", 500, $th);
        }

        return response()->json([
            "message" => "company profile was updated successfully"
        ], 201);
    }

    public function dataTable(UserRepository $repository)
    {
        $companies = $repository->getAllCompany(['profile']); // diganti company nanti
        return DataTables::of($companies)
        ->addIndexColumn()
        ->removeColumn('profile')
        ->removeColumn('email_verified_at')
        ->removeColumn('created_at')
        ->removeColumn('updated_at')
        ->addColumn('username', function($companies) {
            return $companies->profile->username;
        })
        ->addColumn('first_name', function($companies) {
            return $companies->profile->first_name;
        })
        ->addColumn('last_name', function($companies) {
            return $companies->profile->last_name;
        })
        ->addColumn('fullname', function($companies) {
            return $companies->profile->fullname;
        })
        ->addColumn('phone_number', function($companies) {
            return $companies->profile->phone_number;
        })
        ->addColumn('address', function($companies) {
            return $companies->profile->address;
        })
        ->addColumn('action', function($companies) {
            $button = '';

            $button .= '<button id="edit-company" class="px-3 py-1 rounded-full border border-yellow-500 mr-2" onclick="editCompany('.$companies->id.')">Edit</button>';
            $button .= '<button id="delete-company" class="px-3 py-1 rounded-full border border-green-500" onclick="deleteCompany('.$companies->id.')">Delete</button>';

            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getById($id, UserRepository $repository)
    {
        try {
            $company = $repository->getCompanyById($id, 'profile'); // diganti company nanti
            if (empty($company)) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Company with id = ".$id." not found.", 404);
        }
        return $this->success($company);
    }

    public function destroy($id, UserRepository $repository)
    {
        try {
            $company = $repository->deleteUserById($id); // diganti company nanti
            if (!is_bool($company)) {
                return $this->error($company, 500, "Cannot delete Company with id = ".$id.".");
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500, "Cannot delete Company with id = ".$id.".");
        }
        return $this->success("company was deleted successfully");
    }
}
