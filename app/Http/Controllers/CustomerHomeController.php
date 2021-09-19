<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repositories\ProductRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CustomerHomeController extends Controller
{
    use ApiResponser;

    public function index(UserRepository $repository)
    {
        if (empty(auth()->user()->profile->username)) {
            return redirect(route('profile.set'));
        }

        $companies = $repository->getAllCompany();
        return view('home.index', compact(['companies']));
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

    public function setProfile(Request $request)
    {
        $request->validate([
            'username' => ['required', 'alpha_num', 'max:255', 'unique:profiles'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string']
        ]);

        $user = User::find(Auth::user()->id);
        $user->profile()->create([
            "username" => $request['username'],
            "first_name" => $request['first_name'],
            "last_name" => $request['last_name'],
            "phone_number" => $request['phone_number'],
            "address" => $request['address']
        ]);

        return redirect(route('home'));
    }

    public function companyById($id, UserRepository $repository)
    {
        $company = $repository->getCompanyById($id, 'products');
        return view('home.company', compact(['company']));
    }

    public function buyProduk($id, ProductRepository $repository, TransactionRepository $transaction)
    {
        $product = $repository->getProductById($id);
        if ($product->stock > 0) {
            DB::beginTransaction();
            try {
                $product->stock = $product->stock - 1;
                $product->save();

                $result = $transaction->storeTransaction($product);
                if ($result !== true) {
                    return response()->json($result, 500);
                }
            } catch (QueryException $th) {
                DB::rollBack();
                return response()->json($th, 500);
            }
            DB::commit();
            return response()->json('Berhasil dibeli');
        } else {
            return response()->json('Stock habis', 500);
        }
    }
}
