<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\Profile;


class PurchaseMethodTest extends DuskTestCase
{

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testPaymentMethodUpdates()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::factory()->create();
            $user = User::factory()->create([
                'password' => bcrypt('password'), // パスワードを設定
            ]);

            Profile::factory()->create([
                'image' => 'default.png',
                'user_id' => $user->id,
                'post_code' => '123-4567',
                'address' => '東京都新宿区',
                'building' => 'テストビル101'
            ]);

            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('ログインする')
                ->assertPathIs('/');

            $browser->visit("/purchase/{$product->id}")
                ->assertSee('支払い方法')
                ->select('#purchaseMethodSelect', 'カード支払い')
                ->pause(1000)
                ->assertInputValue('#paymentMethodInput', 'カード支払い');
        });
    }
}
