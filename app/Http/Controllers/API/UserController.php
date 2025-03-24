<?php

namespace App\Http\Controllers\API;

use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseController
{
    public function getUser() {
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);
        $user->avatar = $this->getS3Url($user->avatar);
        return $this->sendResponse($user, 'User');
        }
    
        public function uploadAvatar(Request $request)
        {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]);
        
            if ($request->hasFile('image')) {
                try {
                    $authUser = Auth::user();
                    $user = User::findOrFail($authUser->id);
        
                    // Generate unique image name
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $image_name = time() . '_' . $authUser->id . '.' . $extension;
        
                    // Store image in S3
                    $path = $request->file('image')->storeAs(
                        'images',
                        $image_name,
                        's3'
                    );
        
                    // Make the file publicly accessible
                    Storage::disk('s3')->setVisibility($path, "public");
        
                    if (!$path) {
                        return response()->json(['error' => 'User profile avatar failed to upload!'], 500);
                    }
        
                    // Save the avatar path in the database
                    $user->avatar = $path;
                    $user->save();
        
                    // Get the public URL of the uploaded image
                    $avatarUrl = Storage::disk('s3')->url($path);
        
                    // Return response with properly formatted data
                    return response()->json([
                        'results' => ['avatar' => $avatarUrl],
                        'message' => 'User profile avatar uploaded successfully!'
                    ]);
        
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
                }
            } else {
                return response()->json(['error' => 'No file uploaded.'], 400);
            }
        }
        

    public function removeAvatar(){
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);
        Storage::disk('s3')->delete($user->avatar);
        $user->avatar = null;
        $user->save();
        $success['avatar'] = null;
        return $this->sendResponse($success, 'User profile avatar removed successfully!');
    }
}