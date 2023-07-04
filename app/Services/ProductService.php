<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    public function getAll(): Product|Collection|null
    {
        return $this->productRepository->getAllData();
    }

    public function showData(int $id): Product
    {
        return $this->productRepository->getProductById($id);
    }

    public function createProduct(array $data): Product
    {

        $product = $this->productRepository->storeProduct($data);
        $categories = $data['categories'];

        foreach ($categories as $name) {
            $category = $this->categoryRepository->getDataOrCreate($name);
            $category->product()->associate($product);
            $category->save();
        }

        return $product;
    }

    public function updateProduct(int $id, array $data): ?Product
    {
        $product = $this->productRepository->updateProduct($id, $data);
        $categories = $data['categories'];

        $product->categories()->delete();

        foreach ($categories as $name) {
            $category = $this->categoryRepository->getDataOrCreate($name);
            $category->product()->associate($product);
            $category->save();
        }

        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->getProductById($id);

        if ($product) {
            $product->categories()->delete();
            $product->delete();
            return true;
        }

        return false;
    }
}
