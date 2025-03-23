<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\MyList;
use App\Models\Purchase;
use App\Models\Sell;

class MyListTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //いいねした商品だけが表示される
    public function test_my_list_product()
    {
        $user = User::factory()->create()->first();
        $productMyList = Product::factory()->create([
            'name' => '商品_表示',
            'image' => 'test-mylist.jpg'
        ]);
        $productNotMyList = Product::factory()->create([
            'name' => '商品_非表示',
            'image' => 'test-not-mylist.jpg'
        ]);
        $this->actingAs($user);
        MyList::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productMyList->id,
        ]);

        $response = $this->get('/?page=mylist');
        $response->assertSee($productMyList->name);
        $response->assertSee($productMyList->image);
        $response->assertDontSee($productNotMyList->name);
        $response->assertDontSee($productNotMyList->image);
    }

    //Soldが表示される
    public function test_my_list_product_sold_out()
    {
        $user = User::factory()->create()->first();
        $productMyListSoldOut = Product::factory()->create();
        $productMyListNotSoldOut = Product::factory()->create();
        $this->actingAs($user);
        MyList::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productMyListSoldOut->id,
        ]);

        MyList::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productMyListNotSoldOut->id,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productMyListSoldOut->id,
            'post_code' => '160-0023',
            'address' => '東京都新宿区西新宿1-2-3',
            'building' => '新宿グランドタワー 15階'
        ]);

        $response = $this->get('/?page=mylist');

        $response->assertSeeInOrder([
            $productMyListSoldOut->name,
            'Sold'
        ]);
        $response->assertSee($productMyListNotSoldOut->name);
    }

    //自分が出品した商品は表示されない
    public function test_sell_product_no_see()
    {
        $user = User::factory()->create()->first();
        $productSell = Product::factory()->create([
            'name' => '商品_非表示',
            'image' => 'test-not-sell.jpg'
        ]);

        Sell::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productSell->id,
        ]);


        $response = $this->actingAs($user)->get('/?page=mylist');
        $response->assertDontSee($productSell->name);
        $response->assertDontSee($productSell->image);
    }

    //未認証の場合は、何も表示されない
    public function test_no_users_cannot_see_mylist()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => '商品_非表示',
            'image' => 'test.jpg'
        ]);
        MyList::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->get('/?page=mylist');
        $response->assertStatus(200);
        $response->assertSee('マイリスト');
        $response->assertDontSee($product->name);
        $response->assertDontSee($product->image);
    }
}
