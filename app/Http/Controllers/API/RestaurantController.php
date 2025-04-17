<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\ChickenType;
use App\Models\Sauce;

class RestaurantController extends BaseController
{
    
    public function index()
    {
        // Retrieve all restaurants
        $restaurants = Restaurant::with(['sauce', 'chickenType'])->get();

        // Iterate over each restaurant and update the file URL using inherited getS3Url method
        foreach($restaurants as $restaurant) {
            $restaurant->file = $this->getS3Url($restaurant->file); // Calling BaseController's method
        }

        // Return the response with the updated restaurants data
        return $this->sendResponse($restaurants, 'Restaurants');
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'restaurant_name' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG',
            'sauce_name' => 'required_if:has_sauce,true',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $sauce_id = null;
        if($request->has('sauce_name') && !empty($request['sauce_name'])) {
            $sauce = new Sauce();
            $sauce->sauce_name = $request['sauce_name'];
            $sauce->save();
            
            $sauce_id = $sauce->id;
        }

        $restaurant = new Restaurant;

        if ($request->hasFile('file')) {

            $extenstion = request()->file('file')->getClientOriginalExtension();
            $image_name = time() .'_restraunt_cover.' .$extenstion;
            $path = $request->file('file')->storeAs(
                'images',
                $image_name,
                's3'
            );
            Storage::disk('s3')->setVisibility($path, "public");
            if(!$path) {
                return $this->sendError($path, 'Restaurant logo has failed to upload');
            } 

            $restaurant->file = $path;
        }

        $restaurant->restaurant_name = $request['restaurant_name'];
        $restaurant->restaurant_description = $request['restaurant_description'];
        $restaurant->chicken_type_id = $request['chicken_type_id'];
        $restaurant->sauce_id = $sauce_id;

        $restaurant->save();

        if(isset($restaurant->file)) {
            $restaurant->file = $this->getS3Url($restaurant->file);
        }
        $success['restaurant'] = $restaurant;
        return $this->sendResponse($success, 'Restaurant Uploaded');
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'restaurant_name' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        $restaurant = Restaurant::findOrFail($id);
        $restaurant->restaurant_name = $request['restaurant_name'];
        $restaurant->restaurant_description = $request['restaurant_description'];
        $restaurant->chicken_type_id = $request['chicken_type_id'];

        if ($restaurant->sauce_id && $request->has('sauce_name')) {
            // Update existing sauce
            $sauce = Sauce::findOrFail($restaurant->sauce_id);
            $sauce->sauce_name = $request['sauce_name'];
            $sauce->save();
        } else if ($request->has('sauce_name') && !empty($request['sauce_name'])) {
            // Create new sauce
            $sauce = new Sauce();
            $sauce->sauce_name = $request['sauce_name'];
            $sauce->save();
            
            $restaurant->sauce_id = $sauce->id;
        }

        $restaurant->save();

        $success['restaurant'] = $restaurant;
        return $this->sendResponse($success, 'Restaurant Succesfully Updated!');
    }

    public function updateRestaurantPicture(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $restaurant = Restaurant::findOrFail($id);

        if ($request->hasFile('file')) {
        
            $extension  = request()->file('file')->getClientOriginalExtension(); //This is to get the extension of the image file just uploaded
            $image_name = time() .'_restaurant.' . $extension;
            $path = $request->file('file')->storeAs(
                'images',
                $image_name,
                's3'
            );
            Storage::disk('s3')->setVisibility($path, "public");
            if(!$path) {
                return $this->sendError($path, 'Restaurant picture failed to upload!');
            }
            
            $restaurant->file = $path;

        } 
        $restaurant->save();


        if(isset($restaurant->file)){
            $restaurant->file = $this->getS3Url($restaurant->file);
        }
        $success['restaurant'] = $restaurant;
        return $this->sendResponse($success, 'Restaurant picture successfully updated!');
    }

    public function destroy($id) {
        $restaurant = Restaurant::findOrFail($id);
        
        // First check if there's an associated sauce
        if ($restaurant->sauce_id) {
            // Find and delete the associated sauce
            $sauce = Sauce::findOrFail($restaurant->sauce_id);
            $sauce->delete();
        }
        
        // Ensure that the file exists before attempting to delete it
        if ($restaurant->file) {
            Storage::disk('s3')->delete($restaurant->file);
        } else {
            Log::warning('No file found to delete for restaurant ID: ' . $id);
        }
        
        // Now delete the restaurant
        $restaurant->delete();
        
        $success['restaurant']['id'] = $id;
        return $this->sendResponse($success, 'Restaurant and Associated Data Deleted');
    }

    public function getChickenTypes()
    {
        $chickenTypes = ChickenType::all();
        return $this->sendResponse($chickenTypes, 'Chicken Types');
    }
    
}