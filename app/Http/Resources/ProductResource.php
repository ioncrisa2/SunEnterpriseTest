<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductResource extends JsonResource
{
   private String $message;
   private bool $status;
   private Product|Collection|null $product;
   private ?int $statusCode;

   public function __construct(bool $status,String $message,Product|Collection|null $product,int $statusCode)
   {
       $this->message = $message;
       $this->status = $status;
       $this->product = $product;
       $this->statusCode = $statusCode;
   }

   public function toResponse($request)
   {
       $response = [
           'success' => $this->status,
           'message' => $this->message
       ];

       if(!is_null($this->product)){
           $response['data'] = $this->product;
       }

       return response()->json($response,$this->statusCode);
   }
}
