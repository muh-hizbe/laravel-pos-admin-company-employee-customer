<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponser;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    use ApiResponser;

    public function index(UserRepository $repository)
    {
        $customers = $repository->getAllCustomer();
        return view('customer.index', compact(['customers']));
    }

    public function count(UserRepository $repository)
    {
        $count = $repository->getAllCustomer()->count();
        return response()->json($count);
    }

    public function store(UserRegisterRequest $request, UserRepository $repository)
    {
        $validated = $request->validated();

        try {
            $customer = $repository->storeCustomer($validated);
            if ($customer === false) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500);
        }

        return response()->json([
            "message" => "Customer was created successfully"
        ], 201);
    }

    public function updateProfile(UserUpdateRequest $request, ProfileRepository $repository, $id)
    {
        $validated = $request->validated();

        try {
            $customer = $repository->updateUserProfile($id, $validated);
            if ($customer != true) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Customer profile with id = ".$id." cannot be updated.", 500, $th);
        }

        return response()->json([
            "message" => "Customer profile was updated successfully"
        ], 201);
    }

    public function dataTable(UserRepository $repository)
    {
        $customers = $repository->getAllCustomer('profile', true);
        // return response()->json($customers);
        return DataTables::of($customers)
        ->addIndexColumn()
        ->removeColumn('profile')
        ->removeColumn('email_verified_at')
        ->removeColumn('created_at')
        ->removeColumn('updated_at')
        ->addColumn('username', function($customers) {
            return $customers->profile->username;
        })
        ->addColumn('first_name', function($customers) {
            return $customers->profile->first_name;
        })
        ->addColumn('last_name', function($customers) {
            return $customers->profile->last_name;
        })
        ->addColumn('fullname', function($customers) {
            return $customers->profile->fullname;
        })
        ->addColumn('phone_number', function($customers) {
            return $customers->profile->phone_number;
        })
        ->addColumn('address', function($customers) {
            return $customers->profile->address;
        })
        ->addColumn('action', function($customers) {
            $button = '';

            $button .= '<button id="edit-customer" class="px-3 py-1 rounded-full border border-yellow-500 mr-2" onclick="editCustomer('.$customers->id.')">Edit</button>';
            $button .= '<button id="delete-customer" class="px-3 py-1 rounded-full border border-green-500" onclick="deleteCustomer('.$customers->id.')">Delete</button>';

            return $button;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function getById($id, UserRepository $repository)
    {
        try {
            $customer = $repository->getCustomerById($id, 'profile');
            if (empty($customer)) {
                throw new Throwable();
            }
        } catch (\Throwable $th) {
            return $this->error("Customer with id = ".$id." not found.", 404);
        }
        return $this->success($customer);
    }

    public function destroy($id, UserRepository $repository)
    {
        try {
            $customer = $repository->deleteUserById($id);
            if (!is_bool($customer)) {
                return $this->error($customer, 500, "Cannot delete Customer with id = ".$id.".");
            }
        } catch (\Throwable $th) {
            return $this->error($th, 500, "Cannot delete Customer with id = ".$id.".");
        }
        return $this->success("Customer was deleted successfully");
    }
}
