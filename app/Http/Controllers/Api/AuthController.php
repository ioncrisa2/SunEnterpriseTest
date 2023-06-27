<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function Register(RegisterRequest $request): AuthResource
    {
        $request->validated();
        $user = $this->authService->registerUser($request->all());
        return $this->createRegisterResponse($user);
    }

    public function Login(LoginRequest $request): AuthResource
    {
        $request->validated();
        $user = $this->authService->loginUser($request->all());
        return $user !== null
            ? $this->loginSuccessResponse($user)
            : $this->loginFailResponse();
    }

    private function createRegisterResponse(array $data): AuthResource
    {
        return new AuthResource(
            true,
            'User is registered!',
            $data['token'],
            $data['user'],
            Response::HTTP_CREATED
        );
    }

    private function loginSuccessResponse(array $data): AuthResource
    {
        return new AuthResource(
            true,
            'User is logged in!',
            $data['token'],
            $data['user'],
            Response::HTTP_OK
        );
    }

    private function loginFailResponse(): AuthResource
    {
        return new AuthResource(
            false,
            'Email & Password do not match with our records.',
            null,
            null,
            Response::HTTP_NOT_FOUND
        );
    }
}
