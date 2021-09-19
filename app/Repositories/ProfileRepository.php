<?php
namespace App\Repositories;

use App\Models\Profile;
use Illuminate\Database\QueryException;

class ProfileRepository {
    protected $model = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function updateUserProfile($id, $request)
    {
        try {
            $profile = Profile::where('id', $id)->first();
            $profile->first_name = $request['first_name'];
            $profile->last_name = $request['last_name'];
            // // $profile->username = $request->username;
            $profile->phone_number = $request['phone_number'];
            $profile->address = $request['address'];
            $profile->save();
        } catch (QueryException $th) {
            return false;
        }

        return true;
    }
}
