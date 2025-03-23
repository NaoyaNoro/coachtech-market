<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Sell;
use Illuminate\Http\Response;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    //全商品を取得できる
    public function test_get_all_products_date()
    {
        Product::factory()->count(3)->create([
            'name' => 'テスト商品',
            'image' => 'test-image.jpg'
        ]);
        $response = $this->get('/');
        $response->assertStatus(Response::HTTP_OK);
        $products = Product::all();
        foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee($product->image);
        }
    }

    //Soldと表示される
    public function test_sold_out_label()
    {
        $user = User::factory()->create();
        $productPurchased = Product::factory()->create();
        $productNotPurchased = Product::factory()->create();

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productPurchased->id,
            'post_code' => '160-0023',
            'address' => '東京都新宿区西新宿1-2-3',
            'building' => '新宿グランドタワー 15階'
        ]);

        $response = $this->get('/');
        $response->assertSee($productPurchased->name);
        $response->assertSee($productPurchased->image);
        $response->assertSee($productNotPurchased->name);
        $response->assertSee($productNotPurchased->image);

        $response->assertSeeInOrder([
            $productPurchased->name,
            'Sold'
        ]);
    }

    //自分が出品した商品は表示されない
    public function test_sell_product_no_see()
    {
        $user = User::factory()->create()->first();
        $productSell = Product::factory()->create([
            'name' => '商品_非表示',
            'image' => 'test-sell.jpg'
        ]);
        $productNotSell = Product::factory()->create([
            'name' => '商品_表示',
            'image' => 'test-not-sell.jpg'
        ]);

        Sell::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productSell->id,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertSee($productNotSell->name);
        $response->assertSee($productNotSell->image);
        $response->assertDontSee($productSell->name);
        $response->assertDontSee($productSell->image);
    }
}
