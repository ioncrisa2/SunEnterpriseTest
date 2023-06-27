<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductCategoryRelationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_product_can_have_many_categories(): void
    {
        $product = Product::factory()->create();

        $categories = Category::factory()->count(3)->create([
                'product_id' => $product->id
            ]);

        $this->assertCount(3, $product->categories);
        foreach($categories as $category){
            $this->assertEquals($product->id,$category->product_id);
        }
    }

    public function test_categories_can_have_one_product()
    {
        $product = Product::factory()->create();
        $category = Category::factory()->create(['product_id'=>$product->id]);

        $this->assertEquals($product->id,$category->product_id);
    }
}
