<?php
use Illuminate\Support\Facades\Route;
use app\Http\Middleware\AssignGuard;
use App\Http\Controllers\User\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'users'], function () {

        Route::get('showProviders/{token?}', [UserController::class,'showProviders'])->name('showProviders');
        Route::get('reservations/{token?}', [UserController::class,'reservations'])->name('reservations');
        Route::post('addReservation/{token?}', [UserController::class,'addReservation'])->name('addReservation');
        Route::get('addReservation/{token?}', [UserController::class,'addReservationPage'])->name('addReservationPage');
        Route::get('allProviders/{token?}', [UserController::class,'allProviders'])->name('allProviders');
        Route::get('providerDetails/{token?}/{provider_id}', [UserController::class,'providerDetails'])->name('providerDetails');
        Route::get('providerReviews/{token?}/{provider_id}', [UserController::class,'providerReviews'])->name('providerReviews');
        Route::post('addReview/{token?}/{provider_id}', [UserController::class,'addReview'])->name('addReview');
        Route::post('filterProviders/{token?}', [UserController::class,'filterProviders'])->name('filterProviders');
        Route::post('filterByName/{token?}', [UserController::class,'filterByName'])->name('filterByName');
        Route::get('userNotifications/{token?}', [UserController::class,'userNotifications'])->name('userNotifications');
        Route::get('readNotifications/{token?}/{notification_id}', [UserController::class,'readNotifications'])->name('readNotifications');
        Route::get('deleteNotifications/{token?}/{notification_id}', [UserController::class,'deleteNotifications'])->name('deleteNotifications');

});
