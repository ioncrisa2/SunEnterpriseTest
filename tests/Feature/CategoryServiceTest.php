<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;
    private $categoryService;
    private $categoryRepository;
    private $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->categoryService = new CategoryService($this->categoryRepository, $this->productRepository);
    }

    public function testAllData()
    {
        $categories = new Collection([
            new Category(['name' => 'Category 1']),
            new Category(['name' => 'Category 2']),
        ]);

        $this->categoryRepository->expects($this->once())
            ->method('getAllCategories')
            ->willReturn($categories);

        $result = $this->categoryService->allData();

        $this->assertEquals($categories, $result);
    }

    public function testGetCategoryById()
    {
        $category = new Category(['name' => 'Category 1']);

        $this->categoryRepository->expects($this->once())
            ->method('getCategoryById')
            ->with(1)
            ->willReturn($category);

        $result = $this->categoryService->getCategoryById(1);

        $this->assertEquals($category, $result);
    }

    public function testCreateCategory(): void
    {
        $categoryData = [
            'name' => 'New Category',
            'product_id' => 1,
        ];
        $product = new Product(['name' => 'Product 1']);
        $category = new Category($categoryData);
        $category->setRelation('product', $product);

        $this->productRepository->expects($this->once())
            ->method('getProductById')
            ->with(1)
            ->willReturn($product);

        $this->categoryRepository->expects($this->once())
            ->method('storeCategory')
            ->with($categoryData)
            ->willReturn($category);

        $result = $this->categoryService->createCategory($categoryData);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('New Category', $result->name);
        $this->assertEquals($product, $result->product);
    }

    public function testUpdateCategory()
    {
        $categoryId = 1;
        $categoryData = [
            'name' => 'Updated Category',
            'product_id' => 2,
        ];
        $category = new Category(['name' => 'Category 1']);
        $updatedCategory = new Category($categoryData);
        $product = new Product(['name' => 'Product 2']);

        $this->categoryRepository->expects($this->once())
            ->method('getCategoryById')
            ->with($categoryId)
            ->willReturn($category);

        $this->productRepository->expects($this->once())
            ->method('getProductById')
            ->with(2)
            ->willReturn($product);

        $this->categoryRepository->expects($this->once())
            ->method('updateCategory')
            ->with($category->id, $categoryData)
            ->willReturn($updatedCategory);

        $result = $this->categoryService->updateCategory($categoryData, $categoryId);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Updated Category', $result->name);
        $this->assertEquals($product, $result->product);
    }

    public function testDeleteCategory()
    {
        $categoryId = 1;
        $category = new Category(['name' => 'Category 1']);

        $this->categoryRepository->expects($this->once())
            ->method('getCategoryById')
            ->with($categoryId)
            ->willReturn($category);

        $this->categoryRepository->expects($this->once())
            ->method('deleteCategory')
            ->with($categoryId)
            ->willReturn(true);

        $result = $this->categoryService->deleteCategory($categoryId);

        $this->assertTrue($result);
    }

}
