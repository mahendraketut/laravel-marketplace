<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;

class LogoutController extends Controller
{
    use JsonResponseTrait;
    /**
     * logout an account
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->logoutResponse();
    }
}