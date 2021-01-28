<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

//resources

Route::post('user/authorize','App\Http\Controllers\UserController@Authorization');

Route::get('user/posts','App\Http\Controllers\UserController@getPosts');

Route::get('user/profile_info','App\Http\Controllers\UserController@getProfileInfo');

Route::resource('user', 'App\Http\Controllers\UserController');

Route::resource('post', 'App\Http\Controllers\PostController');

Route::resource('follower', 'App\Http\Controllers\FollowerController');

Route::resource('comment', 'App\Http\Controllers\CommentController');

Route::resource('blacklist', 'App\Http\Controllers\BlacklistController');


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
