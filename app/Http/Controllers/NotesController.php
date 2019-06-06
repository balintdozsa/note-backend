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
        $note = $request->all('note');
        if (empty($note)) return response()->json(["status" => "fail"]);

        $id = Auth::id();

        $note = new Notes();
        $note->user_id = $id;
        $note->note = $note;
        $note->save();

        return response()->json(["status" => "ok"]);
    }
}
