<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogleTokensToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_token')) {
                $table->string('google_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'google_refresh_token')) {
                $table->string('google_refresh_token')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_token', 'google_refresh_token']);
        });
    }
}