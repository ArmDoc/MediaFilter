<?php

use App\Http\Controllers\MediaFilterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

Route::prefix('filter')->group(function () {
	Route::post('photo', [MediaFilterController::class, 'detectPhoto']);
	Route::post('video', [MediaFilterController::class, 'detectVideo']);
	Route::post('text', [MediaFilterController::class, 'detectText']);
});
