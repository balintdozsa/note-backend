<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\NotesRepository;

class NotesController extends Controller
{
    private $notesRepository;

    public function __construct(NotesRepository $notesRepository) {
        $this->notesRepository = $notesRepository;
    }

    public function index(Request $request) {
        $user_id = $request->route('user_id');

        $notes = $this->notesRepository->getByUserId($user_id);

        return $notes->toJson();
    }
}
