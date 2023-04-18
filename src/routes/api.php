<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  Noorisyslaravel\Zoom\Controllers\api\ZoomController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::->prefix('package')
Route::post('/create',[ZoomController::class,'User']);
Route::post('/updateUser',[ZoomController::class,'updateUser']);
Route::post('/allUser',[ZoomController::class,'allUser']);
Route::post('/meetings',[ZoomController::class,'meetings']);
Route::post('/createMeeting',[ZoomController::class,'createMeeting']);
Route::post('/updateMeeting',[ZoomController::class,'updateMeeting']);
Route::post('/deleteMeeting',[ZoomController::class,'deleteMeeting']);
Route::post('/endMeeting',[ZoomController::class,'endMeeting']);
// Route::post('/role',[ZoomController::class,'role']);
// Route::post('/webinar',[ZoomController::class,'webinar']);