<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\WalletController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('list', [WalletController::class, 'list']);

    Route::post('create', [WalletController::class, 'create']);

    Route::put('update', [WalletController::class, 'update']);

    Route::delete('delete/{id}', [WalletController::class, 'delete']);
});


Route::post("login",[UserController::class,'index']);