<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends BaseController
{
    
    public function index()
    {
        // Retrieve all restaurants
        $restaurants = Restaurant::get(); 

        // Iterate over each restaurant and update the file URL using inherited getS3Url method
        foreach($restaurants as $restaurant) {
            $restaurant->file = $this->getS3Url($restaurant->file); // Calling BaseController's method
        }

        // Return the response with the updated restaurants data
        return $this->sendResponse($restaurants, 'Restaurants');
    }
}