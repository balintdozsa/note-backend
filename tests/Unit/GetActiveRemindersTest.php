<?php

namespace Tests\Unit;

use App\Models\Note;
use App\Models\NoteReminder;
use App\Models\UserPushToken;
use Carbon\Carbon;
use Tests\TestCase;
use App\Repositories\NoteReminderRepository;

class GetActiveRemindersTest extends TestCase
{
    public function testActiveReminders() {
        $note = factory(Note::class)->create([
            'user_id' => 1,
            'note' => 'Note11',
        ]);

        factory(NoteReminder::class)->create([
            'note_id' => $note->id,
            'utc_notification_time' => Carbon::createFromFormat('Y-m-d H:i:s', '2019-06-17 03:30:00', 'Europe/Budapest'),
            'local_notification_time' => Carbon::createFromFormat('Y-m-d H:i:s', '2019-06-17 03:30:00', 'Europe/Budapest')
                ->setTimezone('UTC'),
            'local_time_zone' => 'Europe/Budapest',
        ]);

        factory(UserPushToken::class)->create([
            'user_id' => 1,
            'push_token' => 1,
        ]);

        $ids = [];
        $notifications = [];

        $nr = new NoteReminderRepository();
        $nr->getActiveReminders($ids, $notifications);

        $this->assertCount(1, $notifications);

        $this->assertEquals('ExponentPushToken[1]', $notifications[0]['to']);
        $this->assertEquals('Note11', $notifications[0]['body']);
    }

    public function tearDown(): void {
        Note::query()->truncate();
        NoteReminder::query()->truncate();
        UserPushToken::query()->truncate();

        parent::tearDown();
    }
}