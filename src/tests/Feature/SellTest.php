<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;


class SellTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格）
    public function test_it_saves_product_and_category_correctly()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $user = User::factory()->create()->first();

        $category1 = Category::factory()->create(['name' => 'ファッション']);
        $category2 = Category::factory()->create(['name' => '家電']);
        $category3 = Category::factory()->create(['name' => '雑貨']);

        $sellData = [
            'image' => UploadedFile::fake()->create('product.jpg', 1024),
            'category' => [$category1->id, $category2->id, $category3->id],
            'color' => 'white',
            'status' => '良好',
            'name' => 'test_product',
            'brand' => 'test_company',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
        ];

        $response = $this->actingAs($user)->post('/sell', $sellData);

        $this->assertDatabaseHas('products', [
            'name' => 'test_product',
            'image' => 'product.jpg',
            'brand' => 'test_company',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
            'color' => 'white',
            'status' => '良好',
        ]);

        $product = Product::where('name', 'test_product')->first();

        $this->assertDatabaseHas('category_products', [
            'product_id' => $product->id,
            'category_id' => $category1->id
        ]);
        $this->assertDatabaseHas('category_products', [
            'product_id' => $product->id,
            'category_id' => $category2->id
        ]);
        $this->assertDatabaseHas('category_products', [
            'product_id' => $product->id,
            'category_id' => $category3->id
        ]);

        $response->assertRedirect('/');
    }
}
