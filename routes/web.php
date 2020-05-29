<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/about', function () {
    return view('frontEnd.layouts.master');
});
///////////Frontend location////////////
Route::get('/viewcart','CartController@index');
Route::get('/','IndexController@index');
Route::get('/list-products','IndexController@shop');
Route::get('/cat/{id}','IndexController@listByCat')->name('cats');
Route::get('/product-detail/{id}','IndexController@detailpro');

/////////////cart Area//////////////////////////

Route::post('addToCart','CartController@addToCart')->name('addToCart');
//named routes ->https://www.javatpoint.com/named-routes-in-laravel

Route::get('/cart/update-quantity/{id}/{quantity}','CartController@updateQuantity');




////////Apply coupon code////////////
Route::post('/apply-coupon','CouponController@applycoupon');
/// Simple User Login /////
Route::get('/login_page','UsersController@index');
Route::post('/register_user','UsersController@register');
Route::post('/user_login','UsersController@login');
Route::get('/logout','UsersController@logout');

////// User Authentications ///////////
Route::group(['middleware'=>'FrontLogin_middleware'],function (){
    Route::get('/myaccount','UsersController@account');
    Route::put('/update-profile/{id}','UsersController@updateprofile');
    Route::put('/update-password/{id}','UsersController@updatepassword');
    Route::get('/check-out','CheckOutController@index');
  //  Route::post('/submit-checkout','CheckOutController@submitcheckout');
//    Route::get('/order-review','OrdersController@index');
  //  Route::post('/submit-order','OrdersController@order');
//    Route::get('/cod','OrdersController@cod');
   // Route::get('/paypal','OrdersController@paypal');
});

Auth::routes();

/*Admin Location*/

Auth::routes(['register'=>false]);
Route::get('/homee', 'HomeController@index');
Route::group(['prefix'=>'admin','middleware'=>['auth','admin']],function (){
    Route::get('/', 'AdminController@index')->name('admin_home');
});

