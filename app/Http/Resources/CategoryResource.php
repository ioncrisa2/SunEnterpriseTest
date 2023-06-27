<?php

namespace App\Http\Resources;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryResource extends JsonResource
{
    private  String $message;
    private bool $status;
    private Category|Collection|null $category;
    private ?int $statusCode;

    public function __construct(bool $status, String $message, Category|Collection|null $category, int $statusCode)
    {
        $this->message = $message;
        $this->status = $status;
        $this->category = $category;
        $this->statusCode = $statusCode;
    }

    public function toResponse($request): JsonResponse
    {
        $response = [
            'success' => $this->status,
            'message' => $this->message
        ];

        if(!is_null($this->category)){
            $response['data'] = $this->category;
        }

        return response()->json($response,$this->statusCode);
    }
}
