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
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG'
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
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
    
        // Ensure that the file exists before attempting to delete it
        if ($restaurant->file) {
            Storage::disk('s3')->delete($restaurant->file);
        } else {
            Log::warning('No file found to delete for restaurant ID: ' . $id);
        }
    
        $restaurant->delete();
    
        $success['restaurant']['id'] = $id;
        return $this->sendResponse($success, 'Restaurant Deleted');
    }

    public function getChickenTypes()
    {
        $chickenTypes = ChickenType::all();
        return $this->sendResponse($chickenTypes, 'Chicken Types');
    }
    
}