<?php

use Illuminate\Http\Request;

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

Route::post('auth/register', 'UserController@register');
Route::post('auth/login', 'UserController@login');
Route::get('auth/logout','UserController@logout')->middleware('auth:api');
Route::get('user', 'UserController@getUser')->middleware('auth:api');
Route::post('postTweet','TweetController@postTweet')->middleware('auth:api');
Route::get('getTweets','TweetController@getTweets')->middleware('auth:api');