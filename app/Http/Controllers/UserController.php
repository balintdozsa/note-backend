<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index() {
        $user = $this->userRepository->getById(Auth::id());

        return response()->json(["data" => $user,]);
    }

    public function setPushToken(Request $request) {
        $token = $request->get('token');

        $this->userRepository->modifyById(Auth::id(), ['push_token' => $token,]);
    }
}