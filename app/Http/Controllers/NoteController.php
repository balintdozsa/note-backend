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
        $notes = $this->noteRepository->getByUserId(Auth::id());

        return response()->json(["data" => $notes,]);
    }

    public function add(Request $request) {
        $addedNote = $request->post('note');
        if (empty($addedNote)) return response()->json(["status" => "fail"]);

        $this->noteRepository->create(['user_id' => Auth::id(), 'note' => $addedNote,]);

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
