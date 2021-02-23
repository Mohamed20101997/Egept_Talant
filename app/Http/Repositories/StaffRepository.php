<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\AuthInterface;
use App\Http\Traits\ApiDesignTrait;

class AuthRepository implements AuthInterface{

    use ApiDesignTrait;

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->ApiResponse(422, 'Unauthorized');
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
        ]);
    }
}
