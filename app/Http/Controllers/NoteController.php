<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Repositories\NoteRepository;
use App\Events\ChangeNote;

class NoteController extends Controller
{
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository) {
        $this->noteRepository = $noteRepository;
    }

    public function index() {
        $notes = $this->noteRepository->getByUserId(Auth::id());

        return response()->json(["data" => $notes,]);
    }

    public function add(Request $request) {
        $addedNote = $request->post('note');
        if (empty($addedNote)) return response()->json(["status" => "fail"]);
        $timeZone = $request->post('time_zone') ?? 'Europe/Budapest';

        $note = $this->noteRepository->create(['user_id' => Auth::id(), 'note' => $addedNote,]);

        event(new ChangeNote($note, $timeZone));

        return response()->json(["status" => "ok"]);
    }

    public function modify(Request $request) {
        $id = $request->post('id');
        $modifiedNote = $request->post('note');
        if (empty($id) || empty($modifiedNote)) return response()->json(["status" => "fail"]);
        $timeZone = $request->post('time_zone') ?? 'Europe/Budapest';

        $note = $this->noteRepository->modifyByIdAndUserId($id, Auth::id(), [
            'note' => $modifiedNote,
            'updated_at' => Carbon::now(),
        ]);

        event(new ChangeNote($note, $timeZone));

        return response()->json(["status" => "ok"]);
    }

    public function delete(Request $request) {
        $id = $request->post('id');
        if (empty($id)) return response()->json(["status" => "fail"]);
        $timeZone = $request->post('time_zone') ?? 'Europe/Budapest';

        $note = $this->noteRepository->deleteByIdAndUserId($id, Auth::id());

        event(new ChangeNote($note, $timeZone, true));

        return response()->json(["status" => "ok"]);
    }
}
