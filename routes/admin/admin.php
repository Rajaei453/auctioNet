<?php
use Illuminate\Support\Facades\Route;
use app\Http\Middleware\AssignGuard;
use App\Http\Controllers\Admin\Auth;
use App\Http\Controllers\Admin\DashboardController;





Route::group(['prefix' => 'admin','namespace'=>'App\Http\Controllers\Admin'], function () {

        Route::get('/login',[Auth\LoginController::class,'showLoginForm'])->name('admin.login');
        Route::post('/login',[Auth\LoginController::class,'login']);
        Route::post('/logout',[Auth\LoginController::class,'logout'])->name('admin.logout');


    Route::get('/home',[DashboardController::class,'index'])->name('admin.home');
    Route::get('/users',[DashboardController::class,'users'])->name('admin.users');
    Route::get('/deleteUser/{user_id}',[DashboardController::class,'deleteUser'])->name('admin.deleteUser');

    Route::get('/providers',[DashboardController::class,'providers'])->name('admin.providers');
    Route::get('/deleteProvider/{provider_id}',[DashboardController::class,'deleteProvider'])->name('admin.deleteProvider');


    Route::get('/categories',[DashboardController::class,'categories'])->name('admin.categories');
    Route::post('/addCategory',[DashboardController::class,'addCategory'])->name('admin.addCategory');
    Route::post('/updatedCategory',[DashboardController::class,'updatedCategory'])->name('admin.updatedCategory');
    Route::get('/deleteCategory/{cat_id}',[DashboardController::class,'deleteCategory'])->name('admin.deleteCategory');


    Route::get('/notifications',[DashboardController::class,'notifications'])->name('admin.notifications');
    Route::get('/deleteNotification/{notification_id}',[DashboardController::class,'deleteNotification'])->name('admin.deleteNotification');

    Route::get('/reservations',[DashboardController::class,'reservations'])->name('admin.reservations');
    Route::get('/deleteReservation/{res_id}',[DashboardController::class,'deleteReservation'])->name('admin.deleteReservations');

    Route::get('/reviews',[DashboardController::class,'reviews'])->name('admin.reviews');
    Route::get('/deleteReview/{review_id}',[DashboardController::class,'deleteReview'])->name('admin.deleteReview');



});
