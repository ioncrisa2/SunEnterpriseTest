<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    private String $message;
    private bool $status;
    private ?String $token;
    private ?User $user;
    private ?int $statusCode;

    public function __construct(bool $status,string $message,?string $accessToken,?User $user,int $statusCode)
    {
        $this->message = $message;
        $this->status = $status;
        $this->token = $accessToken;
        $this->user = $user;
        $this->statusCode = $statusCode;
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => $this->status,
            'message' => $this->message,
            'access_token' => $this->token,
            'user' => $this->user
        ],$this->statusCode);
    }
}
