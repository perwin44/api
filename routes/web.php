<?php

use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
define('PAGINATION_COUNT',10);

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('comment', [App\Http\Controllers\HomeController::class, 'saveComment'])->name('comment.save');


//payment gateways
Route::group(['prefix'=>'offers','middleware'=>'auth'],function(){
    Route::get('/', [App\Http\Controllers\OfferController::class, 'index'])->name('offers.all');
    Route::get('details/{offer_id}', [App\Http\Controllers\OfferController::class, 'show'])->name('offers.show');
});
Route::get('get-checkout-id', [App\Http\Controllers\PaymentProviderController::class, 'getCheckOutId'])->name('offers.checkout');


Route::get('/send-mails',[App\Http\Controllers\HomeController::class, 'sendMails']);
