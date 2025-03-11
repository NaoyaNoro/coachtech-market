<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\User;
use App\Models\MyList;
use App\Models\Comment;
use App\Models\Profile;
use Tests\TestCase;


class DetailTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //必要な情報が表示される+複数選択されたカテゴリーが表示される
    public function test_detail_product()
    {
        $productDetail = Product::factory()->create();

        //category作成
        $category1 = Category::factory()->create(['name' => 'ファッション']);
        $category2 = Category::factory()->create(['name' => 'メンズ']);
        $category3 = Category::factory()->create(['name' => '家電']);

        CategoryProduct::create([
            'product_id' => $productDetail->id, 'category_id' => $category1->id
        ]);
        CategoryProduct::create([
            'product_id' => $productDetail->id, 'category_id' => $category2->id
        ]);
        CategoryProduct::create([
            'product_id' => $productDetail->id, 'category_id' => $category3->id
        ]);

        //いいね作成
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        MyList::factory()->create([
            'user_id' => $user1->id,
            'product_id' => $productDetail->id,
        ]);

        MyList::factory()->create([
            'user_id' => $user2->id,
            'product_id' => $productDetail->id,
        ]);

        //コメント
        Comment::factory()->create([
            'user_id' => $user1->id,
            'product_id' => $productDetail->id,
            'comment'=>'これは美味しい'
        ]);

        Comment::factory()->create([
            'user_id' => $user2->id,
            'product_id' => $productDetail->id,
            'comment' => 'まぁまぁかな'
        ]);

        Comment::factory()->create([
            'user_id' => $user3->id,
            'product_id' => $productDetail->id,
            'comment' => '最高だね。また買いたい。'
        ]);

        $profile1=Profile::factory()->create([
            'user_id' => $user1->id,
            'image' => 'test1.jpg',
            'post_code'=>'111-1111',
            'address'=>'京都府',
            'building'=>'京都第一ビル'
        ]);

        $profile2 = Profile::factory()->create([
            'user_id' => $user2->id,
            'image' => 'test2.jpg',
            'post_code' => '222-2222',
            'address' => '大阪府',
            'building' => '大阪第一ビル'
        ]);

        $profile3 = Profile::factory()->create([
            'user_id' => $user3->id,
            'image' => 'test3.jpg',
            'post_code' => '333-3333',
            'address' => '兵庫',
            'building' => '兵庫第一ビル'
        ]);

        $response = $this->get("/item/{$productDetail->id}");
        //商品画像，商品名，ブランド名，価格，商品説明，商品の状態
        $response->assertStatus(200);
        $response->assertSee($productDetail->image);
        $response->assertSee($productDetail->name);
        $response->assertSee($productDetail->brand);
        $response->assertSee($productDetail->price);
        $response->assertSee($productDetail->description);
        $response->assertSee($productDetail->status);

        //複数のカテゴリーが登録されている
        $response->assertSee($category1->name);
        $response->assertSee($category2->name);
        $response->assertSee($category3->name);

        //いいね数
        $response->assertSee('2');

        //コメント数
        $response->assertSee('3');

        //コメント内容
        $response->assertSee('これは美味しい');
        $response->assertSee('まぁまぁかな');
        $response->assertSee('最高だね。また買いたい。');

        //コメント」したユーザーのプロフィール写真
        $response->assertSee($profile1->image);
        $response->assertSee($profile2->image);
        $response->assertSee($profile3->image);

        //コメントしたユーザーの名前
        $response->assertSee($user1->name);
        $response->assertSee($user2->name);
        $response->assertSee($user3->name);
    }
}
