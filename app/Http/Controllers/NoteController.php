<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Repositories\NoteRepository;
use App\Repositories\NoteReminderRepository;

class NoteController extends Controller
{
    private $noteRepository;
    private $noteReminderRepository;

    public function __construct(NoteRepository $noteRepository, NoteReminderRepository $noteReminderRepository) {
        $this->noteRepository = $noteRepository;
        $this->noteReminderRepository = $noteReminderRepository;
    }

    public function index() {
        $notes = $this->noteRepository->getByUserId(Auth::id());

        return response()->json(["data" => $notes,]);
    }

    public function add(Request $request) {
        $addedNote = $request->post('note');
        if (empty($addedNote)) return response()->json(["status" => "fail"]);

        $note = $this->noteRepository->create(['user_id' => Auth::id(), 'note' => $addedNote,]);

        $patterns = [];
        $patterns[] = "/\d{4}\-\d{2}\-\d{2}\ \d{2}\:\d{2}/"; // "/\d{4}\-\d{2}\-\d{2}|\d{4}\.\d{2}\.\d{2}/";
        $patterns[] = "/\d{4}\.\d{2}\.\d{2}\ \d{2}\:\d{2}/";

        $content = $addedNote;
        $matches = [];
        foreach ($patterns as $pattern) {
            $m = [];
            preg_match_all($pattern, $content, $m);
            array_push($matches, ...$m[0]);
        }

        $timeZone = $request->post('time_zone') ?? 'Europe/Budapest';
        foreach ($matches as $ymd) {
            $ymd = str_replace('.', '-', $ymd);
            $ymd .= ':00';

            $localTime = Carbon::createFromFormat('Y-m-d H:i:s', $ymd, $timeZone);
            $utcTime = Carbon::createFromFormat('Y-m-d H:i:s', $ymd, $timeZone)
                ->setTimezone('UTC');

            $this->noteReminderRepository->create([
                'note_id' => $note->id,
                'utc_notification_time' => $utcTime,
                'local_notification_time' => $localTime,
                'local_time_zone' => 'Europe/Budapest',
            ]);
        }

        return response()->json(["status" => "ok"]);
    }

    public function modify(Request $request) {
        $id = $request->post('id');
        $modifiedNote = $request->post('note');
        if (empty($id) || empty($modifiedNote)) return response()->json(["status" => "fail"]);

        $this->noteRepository->modifyByIdAndUserId($id, Auth::id(), [
            'note' => $modifiedNote,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json(["status" => "ok"]);
    }

    public function delete(Request $request) {
        $id = $request->post('id');
        if (empty($id)) return response()->json(["status" => "fail"]);

        $this->noteRepository->deleteByIdAndUserId($id, Auth::id());

        return response()->json(["status" => "ok"]);
    }
}
