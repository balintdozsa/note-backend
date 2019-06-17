<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Utils\TimeRecognition;
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

        $timeZone = $request->post('time_zone') ?? 'Europe/Budapest';
        $recognizedTimes = TimeRecognition::run($addedNote, $timeZone);

        $this->noteReminderRepository->addReminders($note->id, $recognizedTimes, $timeZone);

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

        $timeZone = $request->post('time_zone') ?? 'Europe/Budapest';
        $recognizedTimes = TimeRecognition::run($modifiedNote, $timeZone);

        $this->noteReminderRepository->deleteByColumns(['note_id' => $id,]);
        $this->noteReminderRepository->addReminders($id, $recognizedTimes, $timeZone);

        return response()->json(["status" => "ok"]);
    }

    public function delete(Request $request) {
        $id = $request->post('id');
        if (empty($id)) return response()->json(["status" => "fail"]);

        $this->noteRepository->deleteByIdAndUserId($id, Auth::id());

        return response()->json(["status" => "ok"]);
    }
}
