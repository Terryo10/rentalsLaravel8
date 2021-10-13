<?php


use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\PropertyCategoriesContoller;
use App\Http\Controllers\PropertyImagesContoller;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SearchContoller;
use App\Http\Controllers\SubscriptionContoller;
use App\Http\Controllers\WishListContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);

Route::resource('properties',PropertiesController::class);
Route::post('update_property_as_taken',[PropertiesController::class, 'setPropertyAsTaken'])->middleware('auth:api');
Route::resource('categories',PropertyCategoriesContoller::class);
Route::get('my_properties',[PropertiesController::class,'userProperties'])->middleware('auth:api');
Route::get('get_price',[PropertiesController::class,'packagePrice']);
Route::post('add_to_wishlist',[WishlistContoller::class,'addToWishlist'])->middleware('auth:api');
Route::get('my_wishlist',[WishlistContoller::class,'userWishList'])->middleware('auth:api');
Route::post('remove_wishlist',[WishlistContoller::class,'removeWishlist'])->middleware('auth:api');
Route::resource('property_images',PropertyImagesContoller::class);
Route::get('single_property/{id}',[PropertiesController::class,'propertySingle']);
Route::post('change_password',[ChangePasswordController::class,'changePassword'])->middleware('auth:api');
Route::get('search', [SearchContoller::class,'index']);
Route::get('check_payment/{id}',[SubscriptionContoller::class ,'checkPayment'])->middleware('auth:api');
Route::post('make_payment',[SubscriptionContoller::class,'makeSubscription'])->middleware('auth:api');
Route::get('get_user_data',[SubscriptionContoller::class,'getUserData'])->middleware('auth:api');


