<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $request->user()->createToken('todoApp')->plainTextToken;

            return $this->response("Authorized", 200, [
                'user' => $user,
                'token' => $token,
            ]);
        }

        return $this->error('Invalid Credentials', 401, []);
    }

    //public function logout(Request $request) {};
}
