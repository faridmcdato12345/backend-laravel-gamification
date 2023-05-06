<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PointController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SocialAuth\FacebookController;
use App\Http\Controllers\Api\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
],function(){
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/forgot_password',[AuthController::class,'fogotPassword']);
    Route::get('/me',[AuthController::class,'show']);
});
Route::group([
    'middleware' => ['api','verified'],
    'prefix' => 'user'
], function(){
    Route::get('/point_per_game', [PointController::class,'pointPerGame']);
    Route::get('/total_points', [PointController::class, 'totalPoints']);
    Route::post('/points',[PointController::class,'store']);
    Route::get('/profile',[ProfileController::class,'me']);
    Route::post('/profile',[ProfileController::class,'store']);
    Route::patch('/profile',[ProfileController::class, 'update']);
    Route::post('/logout',[AuthController::class,'logout']);
});

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class,'__invoke'])
->middleware((['signed','throttle:6,1']))->name('verification.verify');
Route::post('/email/verify/resent', function(Request $request){
    $request->user()->sendEmailVerificationNotification();
    return redirect(env('FRONT_END_URL') . 'user');
})->middleware(['api','throttle:6,1'])->name('verification.send');

// Route::get('/facebook/redirect',[FacebookController::class,'redirect']);
// Route::get('/facebook/callback', [FacebookController::class,'callback']);