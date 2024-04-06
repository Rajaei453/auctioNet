<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\addReservation;
use App\Models\Auction;
use App\Models\AuctionDetail;
use App\Models\Category;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function index(){

        $user = auth('user')->user();


        $auctions = Auction::select('id','name', 'description', 'image','minimum_bid','winner_id','end_time','category_id','status' )->with('category')->get();

        return response()->json(['details'=>$auctions]);

    }
    public function newAuction(Request $request){

        $user = auth('user')->user();

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required',
            'end_time' => 'required',
            // Add more validation rules as needed
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('image')->store('images', 'public');
        // Create a new auction
        $auction = Auction::create([
            'name' => $request->name,
            'description' => $request->description,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'end_time' => $request->end_time,
            'status' => $request->status,
            'image' => $photoPath,
            // Add more fields as needed
        ]);

        // Return a success response with the created auction
        return response()->json(['auction' => $auction], 201);
    }
    public function show($id){

        $user = auth('user')->user();


        $auction = Auction::findOrFail($id);
        return response()->json(['auction' => $auction]);

    }

    public function carAuctions()
    {
        // Get all car auctions where the category ID is 1
        $carAuctions = Auction::whereHas('category', function ($query) {
            $query->where('id', 1); // Assuming category ID 1 represents car auctions
        })->with('details:id,auction_id,brand,model,manufacturing_year,registration_year,engine_type')->get();

        return response()->json($carAuctions);
    }
    public function showCarAuction($id)
    {
        // Fetch the car auction by ID where the category ID is 1
        $carAuction = Auction::where('id', $id)
            ->whereHas('category', function ($query) {
                $query->where('id', 1); // Assuming category ID 1 represents car auctions
            })
            ->with('details:id,auction_id,brand,model,manufacturing_year,registration_year,engine_type')
            ->first();

        if (!$carAuction) {
            return response()->json(['error' => 'Car auction not found'], 404);
        }

        return response()->json($carAuction);
    }
    public function storeCarAuction(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // Define validation rules for the auction data
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string',
            'model' => 'required|string',
            'engine_type' => 'required|string',
            'manufacturing_year' => 'required|integer',
            'registration_year' => 'required|integer',
            'status' => 'required',
            'end_time' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('image')->store('images', 'public');

        // Create a new auction record with the provided data
        $auction = Auction::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $photoPath,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);

        $auction->details()->create([
            'brand' => $request->input('brand'),
            'model' => $request->input('model'),
            'manufacturing_year' => $request->input('manufacturing_year'),
            'registration_year' => $request->input('registration_year'),
            'engine_type' => $request->input('engine_type'),
        ]);
        $carAuction = Auction::where('id', $auction->id)
            ->with('details:id,auction_id,brand,model,manufacturing_year,registration_year,engine_type')
            ->first();

        if (!$carAuction) {
            return response()->json(['error' => 'Car auction not found'], 404);

        }        return response()->json(['auction' => $carAuction], 201);
    }
    public function storeRealEstateAuction(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // Define validation rules for the real estate auction data
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'country' => 'required|string',
            'city' => 'required|string',
            'area' => 'required|string',
            'street' => 'required|string',
            'status' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new auction record with the provided data
        $auction = Auction::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);

        // Create real estate auction details
        $auction->details()->create([
            'country' => $request->input('country'),
            'city' => $request->input('city'),
            'area' => $request->input('area'),
            'street' => $request->input('street'),
            'floor' => $request->input('floor'),
            'total_area' => $request->input('total_area'),
            'num_bedrooms' => $request->input('num_bedrooms'),
            'num_bathrooms' => $request->input('num_bathrooms'),
        ]);

        // Retrieve the newly created real estate auction with details
        $realEstateAuction = Auction::where('id', $auction->id)
            ->with('details:id,auction_id,country,city,area,street,floor,total_area,num_bedrooms,num_bathrooms')
            ->first();

        if (!$realEstateAuction) {
            return response()->json(['error' => 'Real estate auction not found'], 404);
        }

        return response()->json(['auction' => $realEstateAuction], 201);
    }
    public function storeOtherAuction(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // Define validation rules for the other type of auction data
            'name' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new auction record with the provided data
        $auction = Auction::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->image,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);


        $otherAuction = Auction::where('id', $auction->id)->first();

        if (!$otherAuction) {
            return response()->json(['error' => 'Other type of auction not found'], 404);
        }

        return response()->json(['auction' => $otherAuction], 201);
    }



    public function placeBid(Request $request, $id)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'bid_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $auction = Auction::find($id);

        // Check if there are any existing bids
        $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();
        if ($highestBid && $request->input('bid_amount') <= $highestBid->amount) {
            return response()->json(['message' => 'Bid amount must be greater than the current highest bid'], 400);
        }

        // Place the bid
        $bid = new Bid();
        $bid->amount = $request->input('bid_amount');
        $bid->auction_id = $id;
        $bid->user_id = auth()->id(); // Assuming the user is authenticated
        $bid->save();

        $auction->highest_bidder_id = $bid->user_id;
        $auction->save();

        return response()->json(['message' => 'Bid placed successfully'], 200);
    }
    public function getBidHistory($id)
    {
        $auction = Auction::findOrFail($id);
        $bids = $auction->bids()->with('user')->get();
        return response()->json(['bids' => $bids]);
    }
    public function getWinner($id)
    {
        // Retrieve the auction by ID
        $auction = Auction::findOrFail($id);

        // Check if the auction is closed
        if ($auction->status !== 'closed') {
            return response()->json(['message' => 'Auction is not closed'], 400);
        }

        // Determine the winner based on the highest bid
        $winner = $auction->bids()->orderBy('amount', 'desc')->first()->user;

        // Return a JSON response with the winner's information
        return response()->json(['winner' => $winner]);
    }
    public function userNotifications(){
        $user_id = auth('user')->user()->id;

        $user = User::find($user_id);

        $notifications = $user -> notifications ;

        return response()->json(['details'=>$notifications]);
    }
}
