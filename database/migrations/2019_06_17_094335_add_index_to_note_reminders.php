<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToNoteReminders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note_reminders', function (Blueprint $table) {
            $table->index(['note_id'],
                'note_id');

            $table->index(['utc_notification_time','sent',],
                'utc_notification_time-sent');
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
            $table->dropIndex('note_id');

            $table->dropIndex('utc_notification_time-sent');
        });
    }
}
