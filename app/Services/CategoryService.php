<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryService
{
    private $categoryRepository;
    private $productRepository;

    public function __construct(CategoryRepository $categoryRepository,ProductRepository $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function allData(): Collection
    {
        return $this->categoryRepository->getAllCategories();
    }

    public function getCategoryById(int $id): ?Category
    {
        return $this->categoryRepository->getCategoryById($id);
    }

    public function createCategory(array $data): ?Category
    {
        $category = $this->categoryRepository->storeCategory($data);

        if(array_key_exists('product_id',$data)){
            $product = $this->productRepository->getProductById($data['product_id']);

            if($product){
                $category->product()->associate($product);
                $category->save();
            }
        }

        return $category;
    }

    public function updateCategory(array $data,int $id): ?Category
    {
        $category = $this->categoryRepository->getCategoryById($id);

        $updatedCategory = $this->categoryRepository->updateCategory($category->id,$data);

        if(isset($data['product_id'])){
            $productId = $data['product_id'];
            $product = $this->productRepository->getProductById($productId);

            if($product){
                $updatedCategory->product()->associate($product);
            }else{
                $updatedCategory->product()->disassociate();
            }
        }

        $updatedCategory->save();
        return $updatedCategory;
    }

    public function deleteCategory(int $id): bool
    {
        $category = $this->categoryRepository->getCategoryById($id);
        if($category->product()->exists()){
            return false;
        }
        return $this->categoryRepository->deleteCategory($id);
    }

}
