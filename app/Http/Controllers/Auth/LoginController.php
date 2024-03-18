<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;

class LoginController extends Controller
{
    use JsonResponseTrait;
    /**
     * login an account
     */
    public function login(Request $request)
    {
        // Check if the user is already authenticated
        if (Auth::check()) {
            return $this->formatJsonResponse('Anda sudah login', null, null, 200);
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->formatJsonResponse('Email atau password salah', null, null, 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('api_token')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
        ];

        $meta = [
            'ActionAt' => now(),
            'Message' => "Login berhasil"
        ];

        return $this->formatJsonResponse('Login berhasil', $data, $meta, 200);
    }
}
