<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Profile;

class AddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function change_deliverly_address()
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
}
