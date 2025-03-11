<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Sell;

class MypageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    public function test_get_mypage_information()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);
        $purchaseProduct = Product::factory()->create();
        $sellProduct = Product::factory()->create();

        $profile=Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'defalute.jpg',
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $purchaseProduct->id,
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        Sell::factory()->create([
            'user_id' => $user->id,
            'product_id' => $sellProduct->id,
        ]);

        $response = $this->get("/mypage");
        $response->assertSee($user->name);
        $response->assertSee($profile->image);
        $response->assertSee($purchaseProduct->name);
        $response->assertSee($sellProduct->name);
    }
}
