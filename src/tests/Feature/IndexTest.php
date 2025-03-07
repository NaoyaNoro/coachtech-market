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

    /** @test */
    public function get_all_products_date()
    {
        Product::factory()->count(3)->create([
            'name'=>'テスト商品',
            'image'=>'test-image.jpg'
        ]);
        $response=$this->get('/');
        $response->assertStatus(Response::HTTP_OK);
        $products=Product::all();
        foreach($products as $product){
            $response->assertSee($product->name);
            $response->assertSee($product->image);
        }
    }

    /** @test */
    public function sold_out_label()
    {
        $user=User::factory()->create();
        $productPurchased=Product::factory()->create();
        $productNotPurchased=Product::factory()->create();

        Purchase::factory()->create([
            'user_id'=>$user->id,
            'product_id'=>$productPurchased->id,
        ]);

        $response=$this->get('/');
        $response->assertSee($productPurchased->name);
        $response->assertSee($productPurchased->image);
        $response->assertSee($productNotPurchased->name);
        $response->assertSee($productNotPurchased->image);

        $response->assertSeeText('Sold Out');
    }

    /** @test */
    public function sell_product_no_see()
    {
        $user=User::factory()->create()->first();
        $productSell=Product::factory()->create();
        $productNotSell = Product::factory()->create();

        Sell::factory()->create([
            'user_id'=>$user->id,
            'product_id'=>$productSell->id,
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertSee($productNotSell->name);
        $response->assertSee($productNotSell->image);
        $response->assertDontSee($productSell->name);
        $response->assertDontSee($productSell->image);
    }
}
