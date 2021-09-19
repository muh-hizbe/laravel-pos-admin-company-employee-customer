<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRepository {
    protected $model = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getAllUser($relation = null)
    {
        $users = ($relation != null) ? User::with($relation)->get() : User::all();
        return $users;
    }

    public function getAllCompany($relation = null)
    {
        $companies = ($relation != null) ? User::isCompany()->with($relation)->get() : User::isCompany()->get();
        return $companies;
    }

    public function getAllEmployee($relation = null, $filterByCompany = false)
    {
        if ($filterByCompany) {
            $companyId = Auth::user()->id;
            $employees = ($relation != null) ? User::isEmployee()->with($relation)->whereHas('profile', function($query) use($companyId) {
                $query->where('company_id', $companyId);
            })->get() : User::isEmployee()->get();
        } else {
            $employees = ($relation != null) ? User::isEmployee()->with($relation)->get() : User::isEmployee()->get();
        }

        return $employees;
    }

    public function getAllCustomer($relation = null)
    {
        $customers = ($relation != null) ? User::isCustomer()->with($relation)->get() : User::isCustomer()->get();
        return $customers;
    }

    public function storeCompany($request)
    {
        $company = null;

        DB::beginTransaction();
        try {
            $company = User::create([
                "email" => $request['email'],
                "password" => bcrypt($request['password'])
            ]);

            $company->profile()->create([
                "username" => $request['username'],
                "first_name" => $request['first_name'],
                "last_name" => $request['last_name'],
                "phone_number" => $request['phone_number'],
                "address" => $request['address'],
            ]);

            $company->syncRoles(['Company']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
        DB::commit();

        return $company;
    }

    public function storeEmployee($request)
    {
        $employee = null;
        $companyId = Auth::user()->id;

        DB::beginTransaction();
        try {
            $employee = User::create([
                "email" => $request['email'],
                "password" => bcrypt($request['password'])
            ]);

            $employee->profile()->create([
                "username" => $request['username'],
                "first_name" => $request['first_name'],
                "last_name" => $request['last_name'],
                "phone_number" => $request['phone_number'],
                "address" => $request['address'],
                "company_id" => $companyId,
            ]);

            $employee->syncRoles(['Employee']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
        DB::commit();

        return $employee;
    }

    public function storeCustomer($request)
    {
        $customer = null;

        DB::beginTransaction();
        try {
            $customer = User::create([
                "email" => $request['email'],
                "password" => bcrypt($request['password'])
            ]);

            $customer->profile()->create([
                "username" => $request['username'],
                "first_name" => $request['first_name'],
                "last_name" => $request['last_name'],
                "phone_number" => $request['phone_number'],
                "address" => $request['address']
            ]);

            $customer->syncRoles(['Customer']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
        DB::commit();

        return $customer;
    }

    public function getCompanyById($id, $relation = null)
    {
        $company = ($relation != null) ? User::where('id', $id)->isCompany()->with($relation)->first() : User::where('id', $id)->isCompany()->first();
        return $company;
    }

    public function getEmployeeById($id, $relation = null)
    {
        $employee = ($relation != null) ? User::where('id', $id)->isEmployee()->with($relation)->first() : User::where('id', $id)->isEmployee()->first();
        return $employee;
    }

    public function getCustomerById($id, $relation = null)
    {
        $customer = ($relation != null) ? User::where('id', $id)->isCustomer()->with($relation)->first() : User::where('id', $id)->isCustomer()->first();
        return $customer;
    }

    public function getUserById($id, $relation = null)
    {
        $admin = ($relation != null) ? User::where('id', $id)->with($relation)->first() : User::where('id', $id)->first();
        return $admin;
    }

    public function deleteUserById($id)
    {
        try {
            User::find($id)->delete();
        } catch (QueryException $th) {
            return $th;
        }

        return true;
    }

    // public function updateCompanyProfile($id, $request)
    // {
    //     try {
    //         $profile = Profile::where('id', $id)->first();
    //         $profile->first_name = $request['first_name'];
    //         $profile->last_name = $request['last_name'];
    //         // // $profile->username = $request->username;
    //         $profile->phone_number = $request['phone_number'];
    //         $profile->address = $request['address'];
    //         $profile->save();
    //     } catch (QueryException $th) {
    //         return false;
    //     }

    //     return true;
    // }

    // public function updateUserProfile($id, $request)
    // {
    //     try {
    //         $profile = Profile::where('id', $id)->first();
    //         $profile->first_name = $request['first_name'];
    //         $profile->last_name = $request['last_name'];
    //         // // $profile->username = $request->username;
    //         $profile->phone_number = $request['phone_number'];
    //         $profile->address = $request['address'];
    //         $profile->save();
    //     } catch (QueryException $th) {
    //         return false;
    //     }

    //     return true;
    // }
}
