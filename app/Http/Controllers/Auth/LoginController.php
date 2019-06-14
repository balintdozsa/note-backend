<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\PushNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        //$credentials = $request->only('email', 'password');
        $credentials = ['email' => $request->post('email'), 'password' => $request->post('password'),];

        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            $params = [
                'user_id' => Auth::id(),
                'title' => 'You have just logged in',
                'body' => Carbon::now()->format('Y.m.d. H:i:s'),
            ];

            PushNotificationJob::dispatch($params)->delay(now()->addMinutes(1));

            return [
                'token' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ];
        }

        return ['status' => 'failed',];
    }
}
