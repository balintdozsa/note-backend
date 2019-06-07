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

Route::middleware('auth:api')->get('/notes', 'NotesController@index');
Route::middleware('auth:api')->post('/notes/add', 'NotesController@add');
Route::middleware('auth:api')->post('/notes/delete', 'NotesController@delete');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
