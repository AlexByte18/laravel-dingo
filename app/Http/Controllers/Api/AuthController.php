<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use Helpers;

    public function login( AuthRequest $request) : \Dingo\Api\Http\Response {
        $credentials = request(['email', 'password']);

        if( ! $token = JWTAuth::attempt($credentials) ){
            $this->response->error(
                "Credenciales incorrectas",
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $this->responseWithToken($token);
    }

    protected function responseWithToken( $token ) : \Dingo\Api\Http\Response {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in_minutes' => auth('api')->factory()->getTTL()
        ]);
    }
}
