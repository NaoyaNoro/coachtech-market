<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chats = [
            [
                'transaction_id' => 1,
                'sender_id' => 2,
                'message' => "こんにちわ。初めまして。user2と申します"
            ],
            [
                'transaction_id' => 1,
                'sender_id' => 1,
                'message' => "メールありがとうございます。どうかされましたか？"
            ],
            [
                'transaction_id' => 1,
                'sender_id' => 2,
                'message' => "もう少し値下げして欲しいのですが，可能でしょうか？"
            ],
        ];
        foreach ($chats as $chat) {
            DB::table('chats')->insert($chat);
        }
    }
}
