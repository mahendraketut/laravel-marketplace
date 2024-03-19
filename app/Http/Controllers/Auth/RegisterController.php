<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\StoreAccountRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use JsonResponseTrait;
    /**
     * register an account
     */
    public function register(StoreAccountRequest $request)
    {
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]
        );

        $token = $user->createToken('api_token')->plainTextToken;

        return $this->authenticatedResponse(['token' => $token, 'user' => $user]);
    }
}