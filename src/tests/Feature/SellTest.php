<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;
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
    /** @test */
    public function it_saves_product_and_category_correctly()
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

        // POSTリクエストで商品を出品
        $response = $this->actingAs($user)->post('/sell', $sellData);

        $this->assertDatabaseHas('products', [
            'name' => 'test_product',
            'brand' => 'test_company',
            'description' => 'これはテスト商品です。',
            'price' => 10000,
            'color' => 'white',
            'status' => '良好',
        ]);

        // 出品した商品を取得
        $product = Product::where('name', 'test_product')->first();

        // **✅ カテゴリー情報が正しく保存されているか**
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
