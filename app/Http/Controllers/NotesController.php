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
        $note = $request->get('note');
        if (empty($note)) return response()->json(["status" => "fail"]);

        $user_id = Auth::id();

        $newNote = new Notes();
        $newNote->user_id = $user_id;
        $newNote->note = $note;
        $newNote->save();

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
