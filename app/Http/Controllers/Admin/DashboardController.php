<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Provider;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery\Matcher\Not;

class DashboardController extends Controller
{
    public function __construct(){

        $this->middleware('auth:admin');
    }


    public function index(){

        return view('dashboard.index');
    }
    ############################ Users #######################
    public function users(){

        $users = User::get();

        return view('dashboard.users.users',compact('users'));
    }
    public function deleteUser($user_id){

        $user = User::where('id',$user_id)->delete();
        if(!$user)
            return redirect()->route('admin.users')->with(['error'=>'did not find user']);
        return redirect()->route('admin.users')->with(['success'=>'user deleted successfully']);
    }

    ############################ Providers #######################
    public function providers(){

        $providers = Provider::get();

        return view('dashboard.users.providers',compact('providers'));
    }
    public function deleteProvider($provider_id){

        $provider= Provider::where('id',$provider_id)->delete();
        if(!$provider)
            return redirect()->route('admin.providers')->with(['error'=>'did not find provider']);
        return redirect()->route('admin.providers')->with(['success'=>'provider deleted successfully']);
    }
    ############################ Categories #######################

    public function categories(){

        $categories = Category::get();

        return view('dashboard.category.categories',compact('categories'));
    }
    public function addCategory(Request $request){

        $category = Category::create([
            'name'=>$request->name,
        ]);

        if(!$category)
            return redirect()->back()->with(['error'=>'could not add category']);

        return redirect()->back()->with(['success'=>'Category was added successfully']);
    }
    public function updatedCategory(Request $request){

        $category = Category::find($request->category_id)->update([
            'name'=>$request->name
        ]);

        if(!$category)
            return redirect()->back()->with(['error'=>'did not find category']);

        return redirect()->back()->with(['success'=>'Category updated successfully']);
    }
    public function deleteCategory($category_id){

        $category = Category::find($category_id);

        if(!$category)
            return redirect()->back()->with(['error'=>'did not find category']);

        $category->providers()->delete();
        $category->reservations()->delete();
        $category->delete();

        return redirect()->back()->with(['success'=>'Category deleted successfully']);
    }
    ############################ Notifications #######################
    public function notifications(){

        $notifications = Notification::with('user')->get();

        return view('dashboard.notifications',compact('notifications'));

    }
    public function deleteNotification($notification_id){


        $notification = Notification::find($notification_id)->delete();

        if(!$notification)
            return redirect()->back()->with(['error'=>'did not find notification']);

        return redirect()->back()->with(['success'=>'Notification deleted successfully']);

    }
    ############################ Reservations #######################
    public function reservations(){

        $reservations = Reservation::with('category','user','provider')->get();
        foreach ($reservations as $reservation){
            switch($reservation ->approved) {
                case('0'):
                    $reservation->approved = 'pending';
                    break;
                case('1'):
                    $reservation->approved = 'approved';
                    break;
                case('2'):
                    $reservation->approved = 'canceled';
                    break;
            }}
        return view('dashboard.reservation.reservations', compact('reservations'));

    }
    public function deleteReservation($res_id){

        $reservation = Reservation::find($res_id);

        if(!$reservation)
            return redirect()->back()->with(['error'=>'did not find reservation']);

        $reservation->delete();

        return redirect()->back()->with(['success'=>'Reservation deleted successfully']);

    }
    ############################ Reviews #######################
    public function reviews(){

        $providers = Provider::with('reviews')->get();

        return view('dashboard.reviews.reviews',compact('providers'));
    }
    public function deleteReview($review_id){

        $review = Review::find($review_id);

        if(!$review)
            return redirect()->back()->with(['error'=>'did not find review']);

        $review->delete();

        return redirect()->route('admin.reviews')->with(['success'=>'Review deleted successfully']);
    }
    ##########################################################
}

