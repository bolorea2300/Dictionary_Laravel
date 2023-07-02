<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\WordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['session']], function () {
    Route::get('login/{provider}', [UserController::class, 'redirect']);
    Route::get('login/{provider}/callback', [UserController::class, 'callback']);
});

Route::get('/auth/check', [UserController::class, 'check']);
Route::get('/logout', [UserController::class, 'logout']);
Route::post('/user/delete', [UserController::class, 'delete']);
Route::get('/user/history', [UserController::class, 'history']);

Route::get('/dictionary/list', [DictionaryController::class, 'list']);
Route::post('/dictionary/create', [DictionaryController::class, 'create']);
Route::post('/dictionary/update', [DictionaryController::class, 'update']);
Route::post('/dictionary/delete', [DictionaryController::class, 'delete']);
Route::get('/dictionary/{id}', [DictionaryController::class, 'view']);

//単語
Route::post('/word/create', [WordController::class, 'create']);
Route::post('/word/update', [WordController::class, 'update']);
Route::post('/word/delete', [WordController::class, 'delete']);
Route::get('/word/list/{id}', [WordController::class, 'list']);

//タグ
Route::get('/tag/{id}', [DictionaryController::class, 'tag']);

//設定
Route::post('/setting/name', [UserController::class, 'change_name']);
Route::post('/user/block', [UserController::class, 'block']);
