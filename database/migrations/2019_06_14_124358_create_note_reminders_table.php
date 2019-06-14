<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoteRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('note_id');
            $table->timestamp('utc_notification_time')->useCurrent();
            $table->timestamp('local_notification_time')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_reminders');
    }
}
