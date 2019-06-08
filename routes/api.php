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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/notes', 'NotesController@index');
    Route::post('/notes/add', 'NotesController@add');
    Route::post('/notes/modify', 'NotesController@modify');
    Route::post('/notes/delete', 'NotesController@delete');
});

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
