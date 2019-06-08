<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Repositories\NotesRepository;

class NotesController extends Controller
{
    private $notesRepository;

    public function __construct(NotesRepository $notesRepository) {
        $this->notesRepository = $notesRepository;
    }

    public function index() {
        $id = Auth::id();
        $notes = $this->notesRepository->getByUserId($id);

        return response()->json(["data" => $notes,]);
    }

    public function add(Request $request) {
        $addedNote = $request->get('note');
        if (empty($addedNote)) return response()->json(["status" => "fail"]);

        $user_id = Auth::id();

        $newNote = new Notes();
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

        $note = $this->notesRepository->getByIdAndUserId($id, $user_id);
        $note->note = $modifiedNote;
        $note->save();

        return response()->json(["status" => "ok"]);
    }

    public function delete(Request $request) {
        $id = $request->get('id');
        if (empty($id)) return response()->json(["status" => "fail"]);

        $user_id = Auth::id();

        $note = $this->notesRepository->getByIdAndUserId($id, $user_id);
        $note->delete();

        return response()->json(["status" => "ok"]);
    }
}
