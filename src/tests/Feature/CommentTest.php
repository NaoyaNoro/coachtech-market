<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
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
    //ログイン済みのユーザーはコメントを送信できる
    public function test_submit_comment()
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

    //ログイン前のユーザーはコメントを送信できない
    public function test_no_user_cannot_submit_comment()
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

    //コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_validation_check_for_comment()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $product = Product::factory()->create();
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        try {
            $this->post('/comment', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'comment' => ''
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $commentError = $e->errors()['comment'][0];
            $this->assertEquals('コメントを入力してください', $commentError);
            throw $e;
        }
    }

    //コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_more_than_256characters_validation_check()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $product = Product::factory()->create();
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        try {
            $this->post('/comment', [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'comment' => str_repeat('a', 256)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $commentError = $e->errors()['comment'][0];
            $this->assertEquals('255文字以内で入力してください', $commentError);
            throw $e;
        }
    }
}
