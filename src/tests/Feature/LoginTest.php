<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    //emailが入力されていない場合，バリデーションメッセージが表示される.
    public function test_email_is_required_for_login()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        try {
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => '',
                'password' => 'password123',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $emailError = $e->errors()['email'][0];
            $this->assertEquals('メールアドレスを入力してください', $emailError);
            throw $e;
        }
    }

    //passwordが入力されていない場合，バリデーションメッセージが表示される.
    public function test_password_is_required_for_login()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        try {
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => 'test@example.com',
                'password' => '',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $passwordError = $e->errors()['password'][0];
            $this->assertEquals('パスワードを入力してください', $passwordError);
            throw $e;
        }
    }

    //登録情報がない場合，バリデーションメッセージが表示される.
    public function test_no_user_is_for_login()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        try {
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => 'test@example.com',
                'password' => 'password123',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $userError = $e->errors()['email'][0];
            $this->assertEquals('ログイン情報が登録されていません', $userError);
            throw $e;
        }
    }

    //会員登録したユーザーはログインできる
    public function test_login_successful()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $userData = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);

        $userData->assertRedirect('/');
    }
}
