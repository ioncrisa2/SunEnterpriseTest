<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $data): User|array
    {
        $user = $this->userRepository->createUser($data);
        $token = JWTAuth::fromUser($user);
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function loginUser(array $data): User|array|null
    {
       if(!$token = auth()->guard('api')->attempt(['email' => $data['email'],'password' => $data['password']])){
           return null;
       }

       return [
           'user' => auth()->guard('api')->user(),
           'token' => $token
       ];
    }
}
