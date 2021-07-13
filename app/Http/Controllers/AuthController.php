<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{   
    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string'
        ]);

        // $request->validate([
        //     'name'      => 'required|string',
        //     'email'     => 'required|email|unique:users,email',
        //     'password'  => 'required|string'
        // ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->all()], 400);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
        ]);

        return response()->json([
            'message' => 'Registration Success'
        ], 202);
    }

    public function login(Request $request) {
        $creds = $request->only('email', 'password');

        if(!$token = auth()->attempt($creds)) {
            return response()->json([
                'error' => 'Unauthorized'
            ],401);
        }

        return $this->respondWithToken($token);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    public function logout() {
        auth()->logout();
        return response()->json([
            'message' => 'Successfully logged out.'
        ]);
    }

    private function respondWithToken($token) {
        return response()->json([
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60
        ]);
    }
}
