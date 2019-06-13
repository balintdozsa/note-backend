<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePushTokenColumnTypeInUserPushTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_push_tokens', function (Blueprint $table) {
            $table->unique(['push_token'],
                'unique_push_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_push_tokens', function (Blueprint $table) {
            $table->dropUnique('unique_push_token');
        });
    }
}
