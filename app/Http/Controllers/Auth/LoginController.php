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
            return $this->alreadyAuthenticatedResponse();
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->validationErrorResponse(['email' => 'Email atau password salah']);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('api_token')->plainTextToken;

        return $this->authenticatedResponse(['token' => $token, 'user' => $user]);
    }
}
