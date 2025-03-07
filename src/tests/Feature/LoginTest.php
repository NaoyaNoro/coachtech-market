<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_is_required_for_login()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $userData->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_is_required_for_login()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $userData->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    public function test_no_user_is_for_login()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $userData->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

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
