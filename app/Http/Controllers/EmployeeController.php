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

class EmployeeController extends Controller
{
    use ApiResponser;

    public function index(UserRepository $repository)
    {
        $employees = $repository->getAllEmployee();
        return view('employee.index', compact(['employees']));
    }

    public function count(UserRepository $repository)
    {
        $count = $repository->getAllEmployee()->count();
        return response()->json($count);
    }

    public function store(UserRegisterRequest $request, UserRepository $repository)
    {
        $validated = $request->validated();

        try {
            $employee = $repository->storeEmployee($validated);
            if ($employee === false) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500);
        }

        return response()->json([
            "message" => "Employee was created successfully"
        ], 201);
    }

    public function updateProfile(UserUpdateRequest $request, ProfileRepository $repository, $id)
    {
        $validated = $request->validated();

        try {
            $employee = $repository->updateUserProfile($id, $validated);
            if ($employee != true) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Employee profile with id = ".$id." cannot be updated.", 500, $th);
        }

        return response()->json([
            "message" => "Employee profile was updated successfully"
        ], 201);
    }

    public function dataTable(UserRepository $repository)
    {
        $employees = $repository->getAllEmployee('profile', true);
        // return response()->json($employees);
        return DataTables::of($employees)
        ->addIndexColumn()
        ->removeColumn('profile')
        ->removeColumn('email_verified_at')
        ->removeColumn('created_at')
        ->removeColumn('updated_at')
        ->addColumn('username', function($employees) {
            return $employees->profile->username;
        })
        ->addColumn('first_name', function($employees) {
            return $employees->profile->first_name;
        })
        ->addColumn('last_name', function($employees) {
            return $employees->profile->last_name;
        })
        ->addColumn('fullname', function($employees) {
            return $employees->profile->fullname;
        })
        ->addColumn('phone_number', function($employees) {
            return $employees->profile->phone_number;
        })
        ->addColumn('address', function($employees) {
            return $employees->profile->address;
        })
        ->addColumn('action', function($employees) {
            $button = '';

            $button .= '<button id="edit-employee" class="px-3 py-1 rounded-full border border-yellow-500 mr-2" onclick="editEmployee('.$employees->id.')">Edit</button>';
            $button .= '<button id="delete-employee" class="px-3 py-1 rounded-full border border-green-500" onclick="deleteEmployee('.$employees->id.')">Delete</button>';

            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getById($id, UserRepository $repository)
    {
        try {
            $employee = $repository->getEmployeeById($id, 'profile');
            if (empty($employee)) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Employee with id = ".$id." not found.", 404);
        }
        return $this->success($employee);
    }

    public function destroy($id, UserRepository $repository)
    {
        try {
            $employee = $repository->deleteUserById($id);
            if (!is_bool($employee)) {
                return $this->error($employee, 500, "Cannot delete Employee with id = ".$id.".");
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500, "Cannot delete Employee with id = ".$id.".");
        }
        return $this->success("Employee was deleted successfully");
    }
}
