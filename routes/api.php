<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Submission;

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

Route::middleware('auth:airlock')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:airlock')->get('/submission', function (Request $request) {
    $submissions = $request->user()->submissions;
    return response()->json(compact('submissions'));
});



Route::post('/user/register', 'API\RegisterController@register');
Route::post('/user/login', 'API\LoginController@login');