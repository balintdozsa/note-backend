<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeZoneToNoteReminders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note_reminders', function (Blueprint $table) {
            $table->string('local_time_zone', 128)->after('local_notification_time')
                ->default('UTC');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note_reminders', function (Blueprint $table) {
            $table->dropColumn('local_time_zone');
        });
    }
}
