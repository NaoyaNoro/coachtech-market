<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // 名前が入力されていない場合、バリデーションメッセージが表示される.
    public function test_name_is_required_for_registration()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/register', [
            '_token' => csrf_token(),
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $userData->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    // メールアドレスが入力されていない場合、バリデーションメッセージが表示される.
    public function test_email_is_required_for_registration()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $userData->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    // パスワードが入力されていない場合、バリデーションメッセージが表示される.
    public function test_password_is_required_for_registration()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123'
        ]);

        $userData->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    // パスワードが7文字以内の場合、バリデーションメッセージが表示される.
    public function test_password_8characters_is_required_for_registration()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass'
        ]);

        $userData->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    // パスワードと確認用パスワードが一致しない場合、バリデーションメッセージが表示される.
    public function test_password_confirmation_is_required_for_registration()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $userData = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456'
        ]);

        $userData->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    // 全ての項目が入力されている場合、会員情報が登録され、メール認証画面に遷移する
    public function test_register_new_user()
    {
        $this->withoutExceptionHandling();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        Notification::fake();

        $userData = [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        dump(\App\Models\User::all());

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect('/email/verify');

        Notification::assertSentTo(
            User::where('email', 'test@example.com')->first(),
            \Illuminate\Auth\Notifications\VerifyEmail::class
        );
    }
}

