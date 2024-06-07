<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::middleware('auth:sanctum')->get('user',function() {
//     return $request->user();
// });


//all routes/ api here must be authenticated
Route::group(['middleware'=>['api','checkPassword','changeLanguage']],function(){
    Route::post('get-main-categories',[App\Http\Controllers\Api\CategoriesController::class,'index']);
    Route::post('get-category-byId',[App\Http\Controllers\Api\CategoriesController::class,'getCategoryById']);
    Route::post('change-category-status',[App\Http\Controllers\Api\CategoriesController::class,'changeStatus']);


Route::group(['prefix'=>'admin'],function(){
    Route::post('login',[App\Http\Controllers\Api\Admin\AuthController::class,'login']);

    Route::post('logout',[App\Http\Controllers\Api\Admin\AuthController::class,'logout'])->middleware('auth.guard:admin-api');


    //invalidate token security side
    //broken access controller user enumeration
    });

    Route::group(['prefix'=>'user'],function(){
        Route::post('login',[App\Http\Controllers\Api\User\AuthController::class,'UserLogin']);
    });

    Route::group(['prefix'=>'user','middleware'=>'auth.guard:user-api'],function(){
        Route::post('profile',function(){
            return Auth::user();//return authenticated user data
        });
    });
});



Route::group(['middleware'=>['api','checkPassword','changeLanguage','chechAdminToken:admin-api']],function(){
Route::get('offers',[App\Http\Controllers\Api\CategoriesController::class,'changeStatus']);
});
