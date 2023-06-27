<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Exception;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('auth:api');
        $this->categoryService = $categoryService;
    }

    public function index(): CategoryResource
    {
        try{
            $category = $this->categoryService->allData();
            return new CategoryResource(true, 'All Category Data', $category, 200);
        }catch (Exception $e){
            return new CategoryResource(false,'Failed to fetch category data: '.$e->getMessage(),null,500);
        }
    }

    public function show($id): CategoryResource
    {
        try{
            $category = $this->categoryService->getCategoryById($id);
            return new CategoryResource(true,"Detail category with id ".$category->id,$category,200);
        }catch(Exception $e){
            return new CategoryResource(false, 'Failed to fetch category: ' . $e->getMessage(), null, 500);
        }
    }

    public function store(CreateCategoryRequest $request): CategoryResource
    {
        try{
            $request->validated();
            $category = $this->categoryService->createCategory($request->all());
            return new CategoryResource('true',"Success adding category data",$category,201);
        }catch(Exception $e){
            return new CategoryResource(false, 'Failed to create category: ' . $e->getMessage(), null, 500);
        }
    }

    public function update(UpdateCategoryRequest $request, $id): CategoryResource
    {
        try{
            $request->validated();
            $category = $this->categoryService->updateCategory(['name' => $request->name,'product_id' => $request->product_id],$id);
            return new CategoryResource(true,'Category with id '.$category->id.' is updated',$category,200);
        }catch(Exception $e){
            return new CategoryResource(false, 'Failed to update category: ' . $e->getMessage(), null, 500);
        }
    }

    public function destroy(int $id): CategoryResource
    {
        try{
            $category = $this->categoryService->deleteCategory($id);
            if($category !== false){
                return new CategoryResource(true,"Category deleted!",null,204);
            }else{
                return new CategoryResource(false,"Category have relation to product!",null,400);
            }
        }catch(Exception $e){
            return new CategoryResource(false, 'Failed to delete category: ' . $e->getMessage(), null, 500);
        }
    }
}
