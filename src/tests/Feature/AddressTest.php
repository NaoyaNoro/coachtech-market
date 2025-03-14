<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;
use Mockery;
use Stripe\Checkout\Session as StripeSession;

class AddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    public function test_change_deliverly_address()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'defalute.jpg',
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        $profileData = [
            'user_id' => $user->id,
            'post_code' => '456-7890',
            'address' => '大阪府大阪市',
            'building' => '梅田ビル101',
            'product_id'=>$product->id
        ];

        $response = $this->post("/change/address", $profileData);

        $response->assertRedirect("/purchase/{$product->id}");
        $this->get("/purchase/{$product->id}")->assertSee('456-7890');
        $this->get("/purchase/{$product->id}")->assertSee('大阪府大阪市');
        $this->get("/purchase/{$product->id}")->assertSee('梅田ビル101');
    }

    //購入した商品に送付先住所が紐づいて登録される
    public function test_purchase_product_with_deliverly_address()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();
        Profile::factory()->create([
            'image' => 'default.png',
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        $profileData = [
            'user_id' => $user->id,
            'post_code' => '456-7890',
            'address' => '大阪府大阪市',
            'building' => '梅田ビル101',
            'product_id' => $product->id
        ];

        $response = $this->post("/change/address", $profileData);

        $response->assertRedirect("/purchase/{$product->id}");

        $stripeMock = Mockery::mock('alias:' . StripeSession::class);
        $stripeMock->shouldReceive('create')
            ->once()
            ->andReturn((object) [
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/pay/cs_test_123',
            ]);
        $stripeMock->shouldReceive('retrieve')
            ->once()
            ->andReturn((object) [
                'id' => 'cs_test_123',
                'metadata' => (object) [
                    'product_id' => $product->id,
                ],
            ]);

        $purchaseData = [
            'product_name' => $product->name,
            'product_price' => $product->price,
            'product_id' => $product->id,
            'purchase__method' => 'カード支払い',
        ];

        $response = $this->post('/checkout', $purchaseData);
        $response->assertRedirect('https://checkout.stripe.com/pay/cs_test_123');

        $successResponse = $this->get('/payment/success?session_id=cs_test_123');
        $successResponse->assertStatus(200);

        //住所データが紐づいているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'post_code' => '456-7890',
            'address' => '大阪府大阪市',
            'building' => '梅田ビル101',
        ]);

        Mockery::close();
    }
}
