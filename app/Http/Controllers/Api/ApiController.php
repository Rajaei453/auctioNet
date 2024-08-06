<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\AuctionDetail;
use App\Models\Category;
use App\Models\Bid;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function getAllAuctions()
    {
        // Get all auctions
        $auctions = Auction::all();

        // Loop through each auction to determine fields to load based on category ID
        $auctions->each(function ($auction) {
            // Define default fields
            $fields = [];

            // Check the category ID and determine the fields accordingly
            switch ($auction->category_id) {
                case 1: // Car auction
                    $fields = ['brand', 'model', 'manufacturing_year', 'registration_year', 'engine_type'];
                    break;
                case 2: // Real estate auction
                    $fields = ['country', 'city', 'area', 'street', 'floor', 'total_area', 'num_bedrooms', 'num_bathrooms'];
                    break;
                default:
                    break;
            }

            // Eager load the details if fields are defined
            if (!empty($fields)) {
                $auction->load('details:id,auction_id,' . implode(',', $fields));
            }
        });

        return response()->json($auctions);
    }

    public function getUpcomingAuctions()
    {
        try {
            // Get upcoming auctions with status 'pending'
            $auctions = Auction::where('status', 'pending')
                ->where('start_time', '>', now())->orderBy('start_time', 'asc')
                ->get();

            // Loop through each auction to determine fields to load based on category ID
            $auctions->each(function ($auction) {
                // Define default fields
                $fields = [];

                // Check the category ID and determine the fields accordingly
                switch ($auction->category_id) {
                    case 1: // Car auction
                        $fields = ['brand', 'model', 'manufacturing_year', 'registration_year', 'engine_type'];
                        break;
                    case 2: // Real estate auction
                        $fields = ['country', 'city', 'area', 'street', 'floor', 'total_area', 'num_bedrooms', 'num_bathrooms'];
                        break;
                    default:
                        break;
                }

                // Eager load the details if fields are defined
                if (!empty($fields)) {
                    $auction->load('details:id,auction_id,' . implode(',', $fields));
                }
            });

            return response()->json(['auctions' => $auctions], 200);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'Failed to fetch upcoming auctions'], 500);
        }
    }

    public function getSpecificAuction($id)
    {
        // Find the auction by ID
        $auction = Auction::findOrFail($id);

        // Check the category ID of the auction
        $categoryId = $auction->category_id;

        // Define default fields
        $fields = [];

        // Check the category ID and determine the fields accordingly
        switch ($categoryId) {
            case 1: // Car auction
                $fields = ['brand', 'model', 'manufacturing_year', 'registration_year', 'engine_type'];
                break;
            case 2: // Real estate auction
                $fields = ['country', 'city', 'area', 'street', 'floor', 'total_area', 'num_bedrooms', 'num_bathrooms'];
                break;
            default:
                break;
        }

        // Eager load the details if fields are defined
        if (!empty($fields)) {
            $auction->load('details:id,auction_id,' . implode(',', $fields));
        }

        // Get the highest bid amount
        $current_bid = $auction->highestBidAmount();

        // Add the highest bid amount to the response
        $auction->current_bid = $current_bid;

        return response()->json($auction);
    }
    public function closeAuction($id)
    {
        try {
            // Find the auction by its ID
            $auction = Auction::findOrFail($id);

            // Check if the auction is already closed
            if ($auction->status === 'closed') {
                return response()->json(['message' => 'Auction is already closed'], 200);
            }

            // Set the status of the auction to "closed"
            $auction->status = 'closed';
            $auction->save();

            // Return a response indicating success
            return response()->json(['message' => 'Auction closed successfully'], 200);
        } catch (ModelNotFoundException $e) {
            // Handle the case where the auction with the specified ID is not found
            return response()->json(['error' => 'Auction not found'], 404);
        } catch (\Exception $e) {
            // Handle other unexpected errors
            return response()->json(['error' => 'An error occurred while closing the auction'], 500);
        }
    }
    public function getUserAuctions()
    {
        try {
            // Get the authenticated user
            $user = auth('user')->user();

            // Retrieve the user's auctions
            $userAuctions = $user->auctions()->get();
            $userAuctions->each(function ($auction) {
                // Define default fields
                $fields = [];

                // Check the category ID and determine the fields accordingly
                switch ($auction->category_id) {
                    case 1: // Car auction
                        $fields = ['brand', 'model', 'manufacturing_year', 'registration_year', 'engine_type'];
                        break;
                    case 2: // Real estate auction
                        $fields = ['country', 'city', 'area', 'street', 'floor', 'total_area', 'num_bedrooms', 'num_bathrooms'];
                        break;
                    default:
                        break;
                }

                // Eager load the details if fields are defined
                if (!empty($fields)) {
                    $auction->load('details:id,auction_id,' . implode(',', $fields));
                }
            });

            return response()->json($userAuctions);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => 'Failed to retrieve user auctions'], 500);
        }
    }
    public function getUserBids()
    {
        try {
            // Get the authenticated user's ID
            $userId = auth()->user()->id;

            // Find the user
            $user = User::findOrFail($userId);

            // Load the user's bids with associated auction details
            $bids = $user->bids()->with('auction')->get();

            // Return the bids
            return response()->json(['bids' => $bids], 200);
        } catch (\Exception $e) {
            // Handle the error
            return response()->json(['error' => 'Failed to fetch user bids'], 500);
        }
    }

    public function getNotification()
    {
        // Get the authenticated user's notifications
        $notifications = Notification::where('user_id', auth()->id())->orderBy('created_at', 'desc')->with('attachable')->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }


    public function newAuction(Request $request){

        $user = auth('user')->user();

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
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
    public function storeCarAuction(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // Define validation rules for the auction data
            'name' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string',
            'model' => 'required|string',
            'engine_type' => 'required|string',
            'manufacturing_year' => 'required|integer',
            'registration_year' => 'required|integer',
            'status' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'type' => 'required|in:regular,live,anonymous', // Add auction type validation rule
            'increment_amount' => ($request->input('type') === 'live') ? 'required|numeric|min:0' : '', // Conditionally add increment_amount validation rule
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('image')->store('images', 'public');

        // Create a new auction record with the provided data
        $auction = Auction::create([
            'user_id' => auth('user')->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $photoPath,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
            'type' => $request->type, // Store the auction type
            'increment_amount' => ($request->input('type') === 'live') ? $request->increment_amount : null, // Store increment_amount for live auctions
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
        }

        return response()->json(['auction' => $carAuction], 201);
    }

    public function storeRealEstateAuction(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // Define validation rules for the real estate auction data
            'name' => 'required|string',
            'image' => 'required|image',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'country' => 'required|string',
            'city' => 'required|string',
            'area' => 'required|string',
            'street' => 'required|string',
            'status' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'type' => 'required|in:regular,live,anonymous', // Add auction type validation rule
            'increment_amount' => ($request->input('type') === 'live') ? 'required|numeric|min:0' : '', // Conditionally add increment_amount validation rule
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('image')->store('images', 'public');

        // Create a new auction record with the provided data
        $auction = Auction::create([
            'user_id' => auth('user')->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $photoPath,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
            'type' => $request->type, // Store the auction type
            'increment_amount' => ($request->input('type') === 'live') ? $request->increment_amount : null, // Store increment_amount for live auctions
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

    public function realEstateAuctions()
    {
        // Get all real estate auctions where the category ID is 2
        $realEstateAuctions = Auction::whereHas('category', function ($query) {
            $query->where('id', 2); // Assuming category ID 2 represents real estate auctions
        })->with('details:id,auction_id,country,city,area,street,floor,total_area,num_bedrooms,num_bathrooms')->get();

        return response()->json($realEstateAuctions);
    }

    public function storeOtherAuction(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // Define validation rules for the other type of auction data
            'name' => 'required|string',
            'type' => 'required|in:regular,live,anonymous', // Add auction type validation rule
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('image')->store('images', 'public');


        // Create a new auction record with the provided data
        $auction = Auction::create([
            'user_id'=> auth('user')->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $photoPath,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
            'type' => $request->type, // Store the auction type
        ]);


        $otherAuction = Auction::where('id', $auction->id)->first();

        if (!$otherAuction) {
            return response()->json(['error' => 'Other type of auction not found'], 404);
        }

        return response()->json(['auction' => $otherAuction], 201);
    }
    public function storeDecreasingOtherAuction(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            // Define validation rules for the other type of auction data
            'name' => 'required|string',
            'type' => 'required|in:regular,live,anonymous,decreasing', // Add auction type validation rule
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'minimum_bid' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('image')->store('images', 'public');


        // Create a new auction record with the provided data
        $auction = Auction::create([
            'user_id'=> auth('user')->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $photoPath,
            'minimum_bid' => $request->minimum_bid,
            'category_id' => $request->category_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
            'type' => $request->type, // Store the auction type
        ]);


        $otherAuction = Auction::where('id', $auction->id)->first();

        if (!$otherAuction) {
            return response()->json(['error' => 'Other type of auction not found'], 404);
        }

        return response()->json(['auction' => $otherAuction], 201);
    }

    public function otherAuctions()
    {
        // Get auctions with category ID other than 1 and 2, ordered by a specific field (e.g., 'id' or 'created_at')
        $otherAuctions = Auction::whereNotIn('category_id', [1, 2])
            ->orderBy('id', 'asc') // Change 'id' to the desired field
            ->get();

        // Return the JSON response
        return response()->json($otherAuctions);
    }

    public function decreasingAuctions()
    {
        // Get auctions
        $decreasingAuctions = Auction::where('type', 'decreasing')
            ->orderBy('id', 'asc') // Change 'id' to the desired field
            ->get();

        // Return the JSON response
        return response()->json($decreasingAuctions);
    }



    public function placeRegularBid(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'bid_amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $auction = Auction::findOrFail($id);

            // Check if the auction is of type 'regular'
            if ($auction->type !== 'regular') {
                return response()->json(['message' => 'Bids can only be placed on regular auctions'], 400);
            }

            // Check if the auction is closed
            if ($auction->status != 'ongoing') {
                return response()->json(['message' => 'This auction is not open'], 400);
            }

            // Check if the auction has started
            if ($auction->start_time > now()) {
                return response()->json(['message' => 'This auction has not yet started'], 400);
            }

            // Check if the bid amount is less than the auction's minimum bid
            if ($request->input('bid_amount') < $auction->minimum_bid) {
                return response()->json(['message' => 'Bid amount must be greater than or equal to the minimum bid'], 400);
            }

            // Check if the bid amount is higher than the current highest bid
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
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            return response()->json(['error' => 'Failed to place bid'], 500);
        }
    }
    public function placeDecresingBid(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'bid_amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $auction = Auction::findOrFail($id);

            // Check if the auction is of type 'anonymous'
            if ($auction->type !== 'decreasing') {
                return response()->json(['message' => 'Bids can only be placed on decreasing auctions'], 400);
            }

            // Check if the auction is closed
            if ($auction->status != 'ongoing') {
                return response()->json(['message' => 'This auction is not open'], 400);
            }

            // Check if the auction has started
            if ($auction->start_time > now()) {
                return response()->json(['message' => 'This auction has not yet started'], 400);
            }

            // Place the bid
            $bid = new Bid();
            $bid->amount = $request->input('bid_amount');
            $bid->auction_id = $id;
            $bid->user_id = auth()->id(); // Assuming the user is authenticated
            $bid->save();

            // Update the highest bidder ID in the auction
            $auction->highest_bidder_id = auth()->id();
            $auction->save();

            return response()->json(['message' => 'Bid placed successfully'], 200);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            return response()->json(['error' => 'Failed to place bid'], 500);
        }
    }



    protected function placeLiveBid(Request $request, $id)
    {
        try {
            $auction = Auction::findOrFail($id);

            // Check if the auction is closed
            if ($auction->status != 'ongoing') {
                return response()->json(['message' => 'This auction is not open'], 400);
            }

            // Check if the auction has started
            if ($auction->start_time > now()) {
                return response()->json(['message' => 'This auction has not yet started'], 400);
            }

            // Check if the auction type is live
            if ($auction->type !== 'live') {
                return response()->json(['message' => 'This auction is not a live auction'], 400);
            }

            // Get the current highest bid
            $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();

            // Calculate the new bid amount based on the increment amount
            $incrementAmount = $auction->increment_amount;
            $newBidAmount = $highestBid ? $highestBid->amount + $incrementAmount : $auction->minimum_bid;

            // Place the bid
            $bid = new Bid();
            $bid->amount = $newBidAmount;
            $bid->auction_id = $id;
            $bid->user_id = auth()->id(); // Assuming the user is authenticated
            $bid->save();

            $auction->highest_bidder_id = $bid->user_id;
            $auction->save();

            return response()->json(['message' => 'Bid placed successfully'], 200);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            return response()->json(['error' => 'Failed to place bid'], 500);
        }
    }

    public function placeAnonymousBid(Request $request, $id)
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'bid_amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $auction = Auction::findOrFail($id);

            // Check if the auction is of type 'anonymous'
            if ($auction->type !== 'anonymous') {
                return response()->json(['message' => 'Bids can only be placed on anonymous auctions'], 400);
            }

            // Check if the auction is closed
            if ($auction->status != 'ongoing') {
                return response()->json(['message' => 'This auction is not open'], 400);
            }

            // Check if the auction has started
            if ($auction->start_time > now()) {
                return response()->json(['message' => 'This auction has not yet started'], 400);
            }

            // Place the bid
            $bid = new Bid();
            $bid->amount = $request->input('bid_amount');
            $bid->auction_id = $id;
            $bid->user_id = auth()->id(); // Assuming the user is authenticated
            $bid->save();

            // Update the highest bidder ID in the auction
            $auction->highest_bidder_id = auth()->id();
            $auction->save();

            return response()->json(['message' => 'Bid placed successfully'], 200);
        } catch (\Exception $e) {
            // Handle any other unexpected errors
            return response()->json(['error' => 'Failed to place bid'], 500);
        }
    }

    public function updateAuctionDetails(Request $request, $id)
    {
        try {
            // Find the auction
            $auction = Auction::findOrFail($id);

            // Check if the authenticated user is the owner of the auction
            if ($auction->user_id !== auth()->id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string',
                'description' => 'nullable|string',
                'image' => 'nullable|image', // Assuming 'image' should be validated as an image
                'brand' => 'nullable|string',
                'model' => 'nullable|string',
                'manufacturing_year' => 'nullable|integer',
                'registration_year' => 'nullable|integer',
                'engine_type' => 'nullable|string',
                'country' => 'nullable|string',
                'city' => 'nullable|string',
                'area' => 'nullable|string',
                'street' => 'nullable|string',
                'floor' => 'nullable|integer',
                'total_area' => 'nullable|numeric',
                'num_bedrooms' => 'nullable|integer',
                'num_bathrooms' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Handle image upload if present
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('images', 'public'); // Store the image in the 'public/images' directory
                $auction->image = $imagePath;
            }

            // Update auction fields
            $auctionFields = ['name', 'description'];
            foreach ($auctionFields as $field) {
                if ($request->has($field) && !is_null($request->input($field))) {
                    $auction->$field = $request->input($field);
                }
            }

            // Update auction details fields
            $detailFields = [
                'brand', 'model', 'manufacturing_year', 'registration_year',
                'engine_type', 'country', 'city', 'area', 'street',
                'floor', 'total_area', 'num_bedrooms', 'num_bathrooms'
            ];

            $auctionDetails = $auction->details; // Assuming details relationship is defined in Auction model
            foreach ($detailFields as $field) {
                if ($request->has($field) && !is_null($request->input($field))) {
                    $auctionDetails->$field = $request->input($field);
                }
            }

            // Save the updated auction and details
            $auction->save();
            $auctionDetails->save();

            // Fetch the updated auction to include all related details
            $updatedAuction = Auction::with('details')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Auction details updated successfully.',
                'auction' => $updatedAuction
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating auction details.',
                'error' => $e->getMessage()
            ], 500);
        }

    }



    public function getBidHistory($id)
    {
        $auction = Auction::findOrFail($id);

        if ($auction->type === 'anonymous') {
            return response()->json(['message' => 'This auction is anonymous'], 400);
        }
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

    protected $searchableFields = [
        'auctions.name',
        'auctions.type',
        'auctions.description',
        'auctions.image',
        'auctions.minimum_bid',
        'auctions.increment_amount',
        'auctions.highest_bidder_id',
        'auctions.winner_id',
        'auctions.start_time',
        'auctions.end_time',
        'auctions.category_id',
        'auctions.status',
        'auction_details.brand',
        'auction_details.model',
        'auction_details.manufacturing_year',
        'auction_details.registration_year',
        'auction_details.engine_type',
        'auction_details.country',
        'auction_details.city',
        'auction_details.area',
        'auction_details.street',
        'auction_details.floor',
        'auction_details.total_area',
        'auction_details.num_bedrooms',
        'auction_details.num_bathrooms',
    ];

    public function searchRealEstate(Request $request)
    {
        return $this->search($request, 1); // Assuming category_id for real estate is 1
    }

    public function searchCars(Request $request)
    {
        return $this->search($request, 2); // Assuming category_id for cars is 2
    }

    public function searchOthers(Request $request)
    {
        return $this->search($request, 3); // Assuming category_id for others is 3
    }

    public function searchAll(Request $request)
    {
        return $this->search($request, null); // No category filter
    }

    public function searchDecreasingAuctions(Request $request)
    {
        return $this->search($request, 'decreasing'); // Filter for decreasing auctions
    }

    protected function search(Request $request, $filter = null)
    {
        $query = Auction::leftJoin('auction_details', 'auctions.id', '=', 'auction_details.auction_id')
            ->select('auctions.*');

        if (is_numeric($filter)) {
            $query->where('category_id', $filter);
        } elseif ($filter === 'decreasing') {
            $query->where('auctions.type', 'decreasing');
        }

        if ($request->has('q')) {
            $keywords = explode(',', $request->get('q'));
            foreach ($keywords as $keyword) {
                $query->where(function($q) use ($keyword) {
                    foreach ($this->searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%$keyword%");
                    }
                });
            }
        }

        return response()->json($query->get());
    }

}
