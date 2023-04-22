<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\RagisterController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\UserDetailsController;

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

Route::post('ragister',[RagisterController::class,'ragister']); 
Route::post('login',[RagisterController::class,'login']); 

Route::middleware('auth:sanctum')->group( function(){
    Route::get('userdetailsget',[UserDetailsController::class,'getUserDetails']); 
    Route::post('userdetailsstore',[UserDetailsController::class,'store']);
    
    Route::get('userdetailsedit/{id?}',[UserDetailsController::class,'edit']);
    Route::post('userdetailsupdate',[UserDetailsController::class,'update']);
    Route::get('userdetailsdelete/{id?}',[UserDetailsController::class,'delete']);
});
