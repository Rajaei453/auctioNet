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
    Route::get('/details' , [ AuthController::class , 'details'])->middleware('assignGuard:user');
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('assignGuard:user');
    Route::post('/logout' , [ AuthController::class , 'logout']);
});
Route::group([ 'middleware' => [ 'assignGuard:user'], 'prefix' => 'user' ] , function() {
    Route::get('/auctions',[ ApiController::class , 'index']);

    // Routes for Car Auctions
    Route::get('/car-auctions', [ApiController::class, 'carAuctions']);
    Route::get('/car-auctions/{id}', [ApiController::class, 'showCarAuction']);
    Route::post('/car-auctions', [ApiController::class, 'storeCarAuction']);

    // Routes for Real Estate Auctions
    Route::get('/real-estate-auctions', [ApiController::class, 'realEstateAuctions']);
    Route::get('/real-estate-auctions/{id}', [ApiController::class, 'showRealEstateAuction']);
    Route::post('/real-estate-auctions', [ApiController::class, 'storeRealEstateAuction']);

    // Routes for Other Auctions
    Route::get('/other-auctions', [ApiController::class, 'otherAuctions']);
    Route::get('/other-auctions/{id}', [ApiController::class, 'showOtherAuction']);
    Route::post('/other-auctions', [ApiController::class, 'storeOtherAuction']);

    Route::get('/auctions/{id}/bids',[ ApiController::class , 'getBidHistory']);
    Route::post('/auctions/{id}/bid' , [ ApiController::class , 'placeBid']);
    Route::get('/auctions/{id}/winner',[ ApiController::class , 'getWinner']);


});

