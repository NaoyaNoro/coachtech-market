<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments=[
            [
                'product_id'=>1,
                'user_id'=>1,
                'comment'=>'この商品は最高です'
            ],
            [
                'product_id' => 1,
                'user_id' => 3,
                'comment' => 'ちょっとほしいかも'
            ],
            [
                'product_id' => 1,
                'user_id' => 2,
                'comment' => 'これはひどい。二度と買わない'
            ],
            [
                'product_id' => 2,
                'user_id' => 1,
                'comment' => 'これはすごい。めっちゃ早く動くね。'
            ],
        ];
        foreach ($comments as $comment) {
            DB::table('comments')->insert($comment);
        }
    }
}
