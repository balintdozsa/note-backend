<?php

namespace App\Http\Controllers;

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

    public function index(Request $request) {
        $id = Auth::id();
        //Log::info($id);

        $notes = $this->notesRepository->getByUserId($id);

        return (["data" => $notes])->toJson();
    }
}
