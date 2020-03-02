<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->get('/users/current', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/users/get', 'UserController@get');
Route::middleware('auth:api')->get('/users/list', 'UserController@list');

Route::middleware('auth:api')->get('/history', 'HistoryController@get');
Route::middleware('auth:api')->post('/send_to_user', 'OperationsController@sendToUser');
Route::middleware('auth:api')->post('/send_to_users', 'OperationsController@sendToUsers');
