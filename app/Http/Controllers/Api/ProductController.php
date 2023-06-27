<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Exception;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth:api');
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): ProductResource
    {
        try{
            $product = $this->productService->getAll();
            return new ProductResource(true,'All Product Data',$product,200);
        }catch(Exception $e){
            return new ProductResource(false,'Failed to get product data : '. $e->getMessage(),null,500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(CreateProductRequest $request): ProductResource
    {
        try {
            $request->validated();
            $product = $this->productService->createProduct($request->all());

            if ($product) {
                return new ProductResource(true, 'Product Created', $product, 201);
            } else {
                return new ProductResource(false, 'Failed Create product', null, 400);
            }
        } catch (\Exception $e) {
            return new ProductResource(false, 'An error occurred', null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): ProductResource
    {
        try{
            $product = $this->productService->showData($id);
            return new ProductResource(true,"Detail product id ".$product->id,$product,200);
        }catch(Exception $e){
            return new ProductResource(false,"Failed to get Product detail : ".$e->getMessage(),null,500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request,int $id): ProductResource
    {
        try{
            $request->validated();
            $product = $this->productService->updateProduct($id,$request->all());
            return new ProductResource(true,'Product with id'.$product->id.' is updated',$product,200);
        }catch(Exception $e){
            return new ProductResource(false,'Failed to update product : '.$e->getMessage(),null,500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
