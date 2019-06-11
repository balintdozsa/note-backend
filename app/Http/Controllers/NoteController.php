<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Repositories\NoteRepository;

class NoteController extends Controller
{
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository) {
        $this->noteRepository = $noteRepository;
    }

    public function index() {
        $id = Auth::id();
        $notes = $this->noteRepository->getByUserId($id);

        return response()->json(["data" => $notes,]);
    }

    public function add(Request $request) {
        $addedNote = $request->get('note');
        if (empty($addedNote)) return response()->json(["status" => "fail"]);

        $user_id = Auth::id();

        $newNote = new Note();
        $newNote->user_id = $user_id;
        $newNote->note = $addedNote;
        $newNote->save();

        return response()->json(["status" => "ok"]);
    }

    public function modify(Request $request) {
        $id = $request->get('id');
        $modifiedNote = $request->get('note');
        if (empty($id) || empty($modifiedNote)) return response()->json(["status" => "fail"]);

        $user_id = Auth::id();

        $note = $this->noteRepository->getByIdAndUserId($id, $user_id);
        $note->note = $modifiedNote;
        $note->updated_at = Carbon::now();
        $note->save();

        return response()->json(["status" => "ok"]);
    }

    public function delete(Request $request) {
        $id = $request->get('id');
        if (empty($id)) return response()->json(["status" => "fail"]);

        $user_id = Auth::id();

        $note = $this->noteRepository->getByIdAndUserId($id, $user_id);
        $note->delete();

        return response()->json(["status" => "ok"]);
    }
}
