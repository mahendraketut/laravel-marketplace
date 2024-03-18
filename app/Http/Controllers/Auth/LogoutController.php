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
        $data = [
            'user' => $request->user()
        ];
        $meta = [
            'ActionAt' => now(),
            'Message' => "Logout berhasil"
        ];
        return $this->formatJsonResponse('Logout berhasil', $data, $meta, 200);
    }
}
