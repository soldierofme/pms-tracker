<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 他のユーザーシード

        // 子供ユーザーの追加
        User::create([
            'name' => 'Kanekotoko',
            'email' => 'newstoko@toko.com',
            'password' => Hash::make('toko0301'), // 必要に応じてパスワードを変更してください
            'role' => 'child',
        ]);
    }
}
