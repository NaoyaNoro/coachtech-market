<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Purchase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_purchase_stripe_payment()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        $response = $this->get("/purchase/{$product->id}");
        $response->assertSee('購入する');

        $purchaseDate=[
            'product_name'=>$product->name,
            'product_price'=> $product->price,
            'product_id'=>$product->id,
            'purchase__method'=>'カード支払い'
        ];

        $response = $this->post('/checkout',$purchaseDate) ;
        $response->assertRedirectContains('https://checkout.stripe.com');
    }

    //購入した商品は商品一覧画面にて「sold」と表示される
    public function test_sold_out()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();
        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id'=>$product->id,
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        $response = $this->get('/');

        $response->assertSeeInOrder([
            $product->name,
            'Sold'
        ]);
    }

    //「プロフィール/購入した商品一覧」に追加されている
    public function test_mypage_purchase_product()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();
        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        $response = $this->get('/mypage?tab=buy');

        $response->assertSeeInOrder([
            $product->image,
            $product->name,
        ]);
    }
}

