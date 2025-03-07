<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function submit_comment()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $commented = $this->post('/comment', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'これは美味しい'
        ]);
        $commented->assertRedirect("/item/{$product->id}");

        $response = $this->get("/item/{$product->id}");
        $response->assertSee('これは美味しい');
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'これは美味しい'
        ]);

        $response->assertSee('1');
    }

    /** @test */
    public function no_user_cannot_submit_comment()
    {
        $product=Product::factory()->create();
        $user = User::factory()->create();

        $response = $this->post('/comment', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'これは美味しい'
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => 'これは美味しい'
        ]);
    }

    /** @test */
    public function validation_check_for_comment()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $product = Product::factory()->create();
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $comment = $this->post('/comment', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => ''
        ]);

        $comment->assertSee('コメントを入力してください');
    }

    /** @test */
    public function more_than_256characters_validation_check_for_comment()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->withSession([]);

        $product = Product::factory()->create();
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $comment = [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => str_repeat('a', 256)
        ];

        $response = $this->post('/comment', $comment);

        $response->assertSessionHasErrors([
            'comment' => '255文字以内で入力してください'
        ]);
    }
}
