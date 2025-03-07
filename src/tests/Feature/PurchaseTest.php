<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    /** @test */
    public function purchase_test_stripe_payment()
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

        $response = $this->post('/checkout', $purchaseDate) ;$response->assertRedirectContains('https://checkout.stripe.com');
    }
}
