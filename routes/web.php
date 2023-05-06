<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SocialAuth\FacebookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response('If you see this message meaning the backend server is working.');
});
Route::get('/facebook/redirect',[FacebookController::class,'redirect']);
Route::get('/facebook/callback', [FacebookController::class,'callback']);
