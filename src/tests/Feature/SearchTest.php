<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use App\Models\MyList;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    //「商品名」の部分一致で検索することができる
    public function test_search_product()
    {
        $productClock=Product::factory()->create([
            'name' => '腕時計',
            'image' => 'clock-image.jpg'
        ]);
        $productPC = Product::factory()->create([
            'name' => 'PC',
            'image' => 'PC-image.jpg'
        ]);

        $searchData = [
            'name' => '腕',
        ];

        $response = $this->post('/search', $searchData);

        $response = $this->get('/');
        $response->assertSee($productClock->name);
        $response->assertSee($productClock->image);
        $response->assertDontSee($productPC->name);
        $response->assertDontSee($productPC->image);
    }

    //検索状態がマイリストでも保持される
    public function test_search_mylist_product()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);
        $productClock = Product::factory()->create([
            'name' => '腕時計',
            'image' => 'clock-image.jpg'
        ]);
        $productPC = Product::factory()->create([
            'name' => 'PC',
            'image' => 'PC-image.jpg'
        ]);

        MyList::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productClock->id,
        ]);

        MyList::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productPC->id,
        ]);

        $searchData = [
            'name' => '腕',
        ];

        $this->post('/search', $searchData)->assertSessionHas('search_name', '腕');

        $response = $this->get('/?page=mylist');
        $response->assertSee($productClock->name);
        $response->assertSee($productClock->image);
        $response->assertDontSee($productPC->name);
        $response->assertDontSee($productPC->image);
    }
}
