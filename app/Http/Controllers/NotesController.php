<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Notes;

class NotesController extends Controller
{
    public function index(Request $request) {
        $user_id = $request->route('user_id');

        $notes = Notes::where('user_id', $user_id)->get();

        return $notes->toJson();
    }
}
