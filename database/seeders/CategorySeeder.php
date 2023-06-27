<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 10;
        $products = Product::factory()->count(10)->create();

        Category::factory($count)->create()->each(function($category) use ($products){
            $products = $products->random();
            $category->product()->associate($products)->save();

            $additional = rand(1,3);

            for($i = 0;$i < $additional; $i++){
                $newCategory = Category::factory()->create();
                $newCategory->product()->associate($products)->save();
            }
        });
    }
}
