<?php


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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
  Route::get('open', 'DataController@open');
// Books
Route::apiResource('books', 'BookController');
// Ratings
Route::post('books/{book}/ratings', 'RatingController@store')->middleware('auth:api');

// Route::group(['middleware' => 'auth:api'], function() {
//Route::group(['middleware' => 'auth.jwt'], function () {
Route::group(['middleware' => 'jwt.verify'], function () {
    Route::get('articles', 'ArticleController@index');
    Route::get('articles/{article}', 'ArticleController@show');
    Route::post('articles', 'ArticleController@store');
    Route::put('articles/{article}', 'ArticleController@update');
    Route::delete('articles/{article}', 'ArticleController@delete');


      Route::get('user', 'UserController@getAuthenticatedUser');
        Route::get('closed', 'DataController@closed');
});