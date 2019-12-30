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

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});

Route::post('/search', 'Api\ProductController@search')->name('search');

Route::post('/login', 'Api\AuthController@login')->name('login.api');
Route::post('/register', 'Api\AuthController@register')->name('register.api');
Route::post('/sendMail', 'Api\ForgotController@sendMail')->name('sendMail');
Route::group(['prefix' => 'forgot', 'as' => 'forgot'], function () {
    Route::post('sendpassword', 'Api\ForgotController@send_mail')->name('sendpassword');
    Route::post('updatepassword', 'Api\ForgotController@UpdatePassword')->name('updatepassword');
});
Route::group(['prefix' => 'page', 'as' => 'page'], function () {
    Route::get('showPage/', 'Api\PageController@showPages')->name('showPage');
});
Route::group(['prefix' => 'category', 'as' => 'category'], function () {
    Route::get('getCategory/', 'Api\CategoryController@getCategory')->name('getCategory');
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'user', 'as' => 'user'], function () {
        Route::get('logOut/', 'Api\UserController@logOut')->name('logOut');
        Route::get('userInfo/', 'Api\UserController@userInfo')->name('userInfo');
        Route::post('changePassword/', 'Api\UserController@changePassword')->name('changePassword');
        Route::post('EditProfile/', 'Api\UserController@update')->name('EditProfile');
        Route::post('addAddress/', 'Api\UserController@store')->name('addAddress');
    });

    Route::group(['prefix' => 'product', 'as' => 'product'], function () {
        Route::get('getProduct/{id}', 'Api\ProductController@getProduct')->name('getProduct');
        Route::get('getSingleProduct/{id}', 'Api\ProductController@getSingleProduct')->name('getSingleProduct');
        Route::get('getAllProduct/', 'Api\ProductController@getAllProduct')->name('getAllProduct');
    });


    Route::group(['prefix' => 'query', 'as' => 'query'], function () {
        Route::post('store/', 'Api\QueryController@store')->name('store');
    });
    Route::group(['prefix' => 'payment', 'as' => 'payment'], function () {
        Route::post('store/', 'Api\PaymentController@store')->name('store');
        Route::get('getAllOrder/', 'Api\PaymentController@GetOrdersById')->name('getAllOrder');
        Route::get('orderDetail/{id}', 'Api\PaymentController@orderDetail')->name('orderDetail');
    });
});
