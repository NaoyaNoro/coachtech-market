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
    /** @test */
    public function my_list_product(){
        $user = User::factory()->create()->first();
        $productMyList = Product::factory()->create();
        $productNotMyList= Product::factory()->create();
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

    /** @test */
    public function my_list_product_sold_out()
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
        ]);

        $response = $this->get('/?page=mylist');

        $response->assertSeeInOrder([
            $productMyListSoldOut->name,
            'Sold Out'
        ]);
        $response->assertSee($productMyListNotSoldOut->name);
    }

    /** @test */
    public function sell_product_no_see()
    {
        $user = User::factory()->create()->first();
        $productSell = Product::factory()->create();

        Sell::factory()->create([
            'user_id' => $user->id,
            'product_id' => $productSell->id,
        ]);


        $response = $this->actingAs($user)->get('/?page=mylist');
        $response->assertDontSee($productSell->name);
        $response->assertDontSee($productSell->image);
    }

    /** @test */
    public function no_users_cannot_see_mylist()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
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
