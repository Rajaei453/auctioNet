<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;

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

Route::group([ 'prefix' => 'user' ] , function() {
    Route::post('/register' , [ AuthController::class , 'userRegister']);
    Route::post('/login' , [ AuthController::class , 'login']);
    Route::post('/details' , [ AuthController::class , 'details']);
    Route::post('/logout' , [ AuthController::class , 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});
Route::group([ 'middleware' => [ 'assignGuard:user'], 'prefix' => 'user' ] , function() {
    Route::get('/auctions',[ ApiController::class , 'index']);
    Route::post('/auctions', [ApiController::class, 'newAuction']);
    Route::get('/auctions/{id}',[ ApiController::class , 'show']);
    Route::get('/auctions/{id}/bids',[ ApiController::class , 'getBidHistory']);
    Route::post('/auctions/{id}/bid' , [ ApiController::class , 'placeBid']);
    Route::get('/auctions/{id}/winner',[ ApiController::class , 'getWinner']);


});

