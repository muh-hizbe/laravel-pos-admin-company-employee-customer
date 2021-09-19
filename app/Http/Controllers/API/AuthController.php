<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\{UserLoginRequest, UserRegisterRequest};
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->profile()->create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
        ]);

        $token = $user->createToken('pos-joseph')->plaintTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }

    public function logoutAll()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'All Tokens Revoked'
        ];
    }

    public function login(UserLoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return $this->error('Credentials not match', 401);
        }

        $roles = auth()->user()->getRoleNames()->first();
        return $this->success([
            'user' => auth()->user()->with('roles'),
            'token' => auth()->user()->createToken('pos-joseph', ['serve:'.$roles])->plainTextToken
        ]);

        // dd($validated);

        // $user = User::where('email', $request['email'])->first();

        // if (!$user || Hash::check($request['password'], $user['password'])) {
        //     return response()->json([
        //         'message' => 'These credentials do not match our records'
        //     ]);
        // }

        // $token = $user->createToken('pos-joseph')->plaintTextToken;

        // return response()->json([
        //     'user' => $user,
        //     'token' => $token
        // ], 200);
    }
}
