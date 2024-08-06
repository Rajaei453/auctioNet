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

Route::group(['prefix' => 'user'], function () {
    Route::post('/register', [AuthController::class, 'userRegister']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/details', [AuthController::class, 'details'])->middleware('assignGuard:user');
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('assignGuard:user');
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/auctions', [ApiController::class, 'getAllAuctions']);
    Route::get('/upcoming-auctions', [ApiController::class, 'getUpcomingAuctions']);
    Route::get('/specificAuction/{id}', [ApiController::class, 'getSpecificAuction']);

    // Routes for Car Auctions
    Route::get('/car-auctions', [ApiController::class, 'carAuctions']);

    // Routes for Real Estate Auctions
    Route::get('/real-estate-auctions', [ApiController::class, 'realEstateAuctions']);

    // Routes for Other Auctions
    Route::get('/other-auctions', [ApiController::class, 'otherAuctions']);

    // Routes for Bids
    Route::get('/auctions/{id}/winner', [ApiController::class, 'getWinner']);
});

//////////////////////////////////////////auth routes/////////////////////////////////////////////
Route::group(['middleware' => ['assignGuard:user'], 'prefix' => 'user'], function () {
    // Routes for Auctions
    Route::get('/auctions/{id}/close', [ApiController::class, 'closeAuction']);
    Route::get('/my-auctions', [ApiController::class, 'getUserAuctions']);
    Route::post('/auctions/{id}/editdetails', [ApiController::class, 'updateAuctionDetails']); // Resolved conflict by keeping POST
    Route::get('/my-bids', [ApiController::class, 'getUserBids']);
    Route::get('/notifications', [ApiController::class, 'getNotification']);

    // Routes for Car Auctions
    Route::post('/car-auctions', [ApiController::class, 'storeCarAuction']);

    // Routes for Real Estate Auctions
    Route::post('/real-estate-auctions', [ApiController::class, 'storeRealEstateAuction']);

    // Routes for Other Auctions
    Route::post('/other-auctions', [ApiController::class, 'storeOtherAuction']);
    Route::post('/decreasing-other-auctions', [ApiController::class, 'storeDecreasingOtherAuction']);

    // Routes for Bids
    Route::get('/auctions/{id}/bids', [ApiController::class, 'getBidHistory']);
    Route::post('/regular-auctions/{id}/bid', [ApiController::class, 'placeRegularBid']);
    Route::post('/live-auctions/{id}/bid', [ApiController::class, 'placeLiveBid']);
    Route::post('/anonymous-auctions/{id}/bid', [ApiController::class, 'placeAnonymousBid']);
    Route::post('/decreasing-auctions/{id}/bid', [ApiController::class, 'placeDecresingBid']);
});
