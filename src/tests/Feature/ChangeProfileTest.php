<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Profile;
use App\Models\Product;
use App\Models\Sell;

class ChangeProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function change_profile_information()
    {
        $user = User::factory()->create()->first();
        $this->actingAs($user);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'defalute.jpg',
            'post_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル101'
        ]);

        $response = $this->get("/mypage/profile");
        $response->assertSee($user->name);
        $response->assertSee($profile->image);
        $response->assertSee($profile->post_code);
        $response->assertSee($profile->address);
        $response->assertSee($profile->building);
    }
}