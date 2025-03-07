<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;


class GoodTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function good_button_check()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response_good = $this->post('/good_button', [
            'product_id' => $product->id
        ]);

        $this->assertDatabaseHas('mylists', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $response_good->assertStatus(302);

        $response_detail = $this->get("/item/{$product->id}");
        $response_detail->assertSee('1');
    }

    /** @test */
    public function good_button_icon_color_change() {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->post('/good_button', [
            'product_id' => $product->id
        ]);

        $this->assertDatabaseHas('mylists', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $response->assertRedirect("/item/{$product->id}");

        $response = $this->get("/item/{$product->id}");

        $response->assertSee('checked');
    }

    /** @test */
    public function good_button_again_check()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $product = Product::factory()->create();

        $response = $this->post('/good_button', [
            'product_id' => $product->id
        ]);

        $response = $this->post('/good_button', [
            'product_id' => $product->id
        ]);

        $this->assertDatabaseMissing('mylists', [
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $response->assertRedirect("/item/{$product->id}");

        $response = $this->get("/item/{$product->id}");

        $response->assertDontSee('checked');
    }
}
