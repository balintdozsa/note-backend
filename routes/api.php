<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'Auth\LoginController@authenticate');
Route::post('/user/unsetPushToken', 'UserController@unsetPushToken');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/user', 'UserController@index');
    Route::post('/user/revokeUserToken', 'UserController@revokeUserToken');
    Route::post('/user/setPushToken', 'UserController@setPushToken');

    Route::get('/note', 'NoteController@index');
    Route::post('/note/add', 'NoteController@add');
    Route::post('/note/modify', 'NoteController@modify');
    Route::post('/note/delete', 'NoteController@delete');
});

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
