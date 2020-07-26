<?php

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

Route::middleware('auth:sanctum')->prefix('v1')->group(function(){
    
    // Wishlists routes
    Route::get('wishlists', 'WishlistController@index');

    Route::get('wishlist/{wishlist}', 'WishlistController@show');
    
    Route::post('wishlist', 'WishlistController@store');
    
    Route::patch('wishlist/{wishlist}', 'WishlistController@update');

    Route::delete('wishlist/{wishlist}', 'WishlistController@destroy');

    // Wishlist items routes
    Route::post('wishlist/{wishlist}/item/create', 'WishlistItemsController@store');
    
    Route::patch('wishlist/{wishlist}/item/{wishlist_item}', 'WishlistItemsController@update');
    
    Route::delete('wishlist/{wishlist}/item/{wishlist_item}', 'WishlistItemsController@destroy');

});
