<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);
Route::post('/logout', [RegisterController::class, 'logout']);

Route::middleware('auth:sanctum')->group( function () {
    Route::controller(UserController::class)->group(function(){
    Route::get('user', 'getUser');
    Route::post('user/upload_avatar', 'uploadAvatar');
    Route::delete('user/remove_avatar','removeAvatar');
    Route::post('user/send_verification_email','sendVerificationEmail');
    Route::post('user/change_email', 'changeEmail');
    });
    });