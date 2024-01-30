<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//get all albums
Route::get('/albums',[AlbumController::class,'index']);
//get all images, with filter
Route::get('/images',[ImageController::class,'index']);
//get a single image
Route::get('/images/{image}',[ImageController::class,'show']);

//register user
Route::post('/register',[UserController::class,'store']);
//login user
Route::post('/login',[UserController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']],function(){
    //create new image
    Route::post('/images',[ImageController::class,'store']);
    //update a single image
    Route::patch('/images/{image}',[ImageController::class,'update']);
    //delete a single image
    Route::delete('/images/{image}',[ImageController::class,'destroy']);
    //upload an image
    Route::post('/images/upload',[ImageController::class,'upload']);

    //logout user
    Route::post('/logout',[UserController::class,'logout']);
});