<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use app\Http\controllers\api\AuthController;
use App\Http\controllers\api\mainController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::get('governorates', 'MainController@governorates');
    Route::get('cities','MainController@cities');
    Route::post('register', 'AuthController@register');
    Route::post('login',[authController::class,'login']);
    Route::get('bloodtype',[mainController::class,'bloodType']);
    Route::get('category',[mainController::class,'category']);
    Route::get('notification',[mainController::class,'notifications']);
    Route::post('editprofile',[mainController::class,'editprofile']);
    Route::post('editNotification',[mainController::class,'update']);
    Route::get('contacts',[mainController::class,'contacts']);
    Route::post('resetPassword',[mainController::class,'resetPassword']);
    Route::post('isfavourite',[mainController::class,'postFavourite']);
    Route::get('myFavourite',[mainController::class,'FavouriteMe']);
    Route::post('insertCity',[mainController::class,'createCity']);
    Route::get('governorate',[mainController::class,'governorate']);
    Route::post('insertGovernorate',[mainController::class,'createGonvernorate']);

    Route::group(['middleware'=>'auth:api'],function(){
        Route::get('posts', [mainController::class,'posts']);
        Route::post('newpass', [mainController::class,'verifNew']);
        Route::post('settings', [mainController::class,'setting']);
        Route::post('donationRequest', [mainController::class,'createDonationRequest']);
    });

});
