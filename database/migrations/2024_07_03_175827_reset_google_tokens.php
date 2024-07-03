<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ResetGoogleTokens extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('users', 'google_token') && Schema::hasColumn('users', 'google_refresh_token')) {
            DB::table('users')->update([
                'google_token' => null,
                'google_refresh_token' => null
            ]);
        }
    }

    public function down()
    {
        // ロールバック時の処理（必要に応じて）
    }
}