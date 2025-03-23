<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;
use Tests\TestCase;
use Mockery;
use Stripe\Checkout\Session as StripeSession;


class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //「購入する」ボタンを押下すると購入が完了する
    //購入した商品は商品一覧画面にて「sold」と表示される
    //「プロフィール/購入した商品一覧」に追加されている
    public function test_purchase_stripe_payment()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();
        Profile::factory()->create([
            'image'=>'default.png',
            'user_id' => $user->id,
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

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

        //「購入する」ボタンを押下すると購入が完了する
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        //購入した商品は商品一覧画面にて「sold」と表示される
        $responseSoldout = $this->get('/');
        $responseSoldout->assertSeeInOrder([
            $product->name,
            'Sold'
        ]);

        //「プロフィール/購入した商品一覧」に追加されている
        $responseMyPage = $this->get('/mypage?tab=buy');
        $responseMyPage->assertSeeInOrder([
            $product->image,
            $product->name,
        ]);

        Mockery::close();
    }
}

