<?php

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
Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});
Route::get('/', function () {
    return redirect(route('index'));
});


Route::get('/authentication', 'Web\AuthController@authentication')->name('authentication');
Route::get('contact', 'Web\PageController@contact')->name('contact');
Route::get('help', 'Web\PageController@help')->name('help');
Route::get('cart', 'Web\CartController@cart')->name('cart');
Route::get('category', 'Web\CategoryController@index')->name('category');
Route::get('category/{id}', 'Web\CategoryController@FilterCategory')->name('categoryy');
Route::get('shop', 'Web\ShopController@index')->name('shop');
Route::get('logout', 'Web\AuthController@logout')->name('logout');
Route::get('account', 'Web\UserController@account')->name('account');
Route::get('/index', 'Web\UserController@index')->name('index');
Route::post('register', 'Web\AuthController@registerUser')->name('register');
Route::post('login', 'Web\AuthController@doLogin')->name('login');
Route::post('changePassword', 'Web\UserController@change_password')->name('changePassword');
Route::get('product-detail/{id}', 'Web\CategoryController@productDetail')->name('product-detail');

Route::post('addAddress', 'Web\UserController@store')->name('addAddress');
Route::post('/UserUpdate', 'Web\UserController@update')->name('UserUpdate');
Route::post('/send_mail', 'Web\ForgotController@send_mail')->name('send_mail');
Route::post('/addCart', 'Web\CartController@store')->name('addCart');
Route::get('/removeItem/{id}', 'Web\CartController@removeItem')->name('removeItem');
Route::get('/signleCart/{id}', 'Web\CartController@singleCart')->name('signleCart');
Route::get('/order', 'Web\PageController@order')->name('order');
Route::get('/thankyou', 'Web\PageController@thankyou')->name('thankyou');

Route::post('/AjaxCart', 'Web\CartController@AjaxCart')->name('AjaxCart');

Route::get('/checkout', 'Web\ShopController@checkout')->name('checkout');
Route::post('checkoutPayment', 'Web\PaymentController@store')->name('checkoutPayment');

Route::get('MailReciept', 'Web\PaymentController@MailReciept')->name('MailReciept');

Route::get('order-detail/{id}', 'Web\PageController@orderDetail')->name('order-detail');

Route::get('term', 'Web\PageController@term')->name('term');
Route::get('privacy', 'Web\PageController@privacy')->name('privacy');

Route::get('about_us', 'Web\PageController@about')->name('about_us');

Route::post('contactStore', 'Web\PageController@contactStore')->name('contactStore');

Route::get('search', 'Web\ShopController@search')->name('search');

Route::post('show_term', 'Web\AuthController@show_term')->name('show_term');

Route::post('changeState', 'Web\UserController@changeState')->name('changeState');


// Route::get('/admin', function () {
//     return redirect(route('admnin/login'));
// });
Route::get('admin/login', 'Admin\MainController@login')->name('admnin/login');
Route::get('admin/logout', 'Admin\MainController@Alogout')->name('admin/logout');
Route::post('admin_login', 'Admin\MainController@do_login')->name('admin_login');
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    Route::get('/', 'Admin\ProductController@show')->name('product');
    Route::get('/view-category', 'Admin\MainController@category')->name('view-category');
    Route::get('/payment', 'Admin\MainController@payment')->name('payment');
    Route::get('/addPopup', 'Admin\CategoryController@addPopup')->name('addPopup');
    Route::get('/addPopupProduct', 'Admin\ProductController@addPopup')->name('addPopupProduct');
    Route::post('addCategory', 'Admin\CategoryController@store')->name('addCategory');
    Route::post('addProduct', 'Admin\ProductController@store')->name('addProduct');
    Route::get('/viewPaymentPopup', 'Admin\ProductController@viewPaymentPopup')->name('viewPaymentPopup');
    Route::get('/editPaymentPopup', 'Admin\ProductController@editPaymentPopup')->name('editPaymentPopup');
    Route::get('/view_product/{id}', 'Admin\ProductController@view_product')->name('view_product');
    Route::get('/edit_product/{id}', 'Admin\ProductController@edit_product')->name('edit_product');

    Route::post('/UpdateProduct/{id}', 'Admin\ProductController@UpdateProduct')->name('UpdateProduct');
    Route::get('/deleteImages/{id}', 'Admin\ProductController@deleteImages')->name('deleteImages');

    Route::get('/addImageGaleryPopup/{id}', 'Admin\ProductController@addImageGaleryPopup')->name('addImageGaleryPopup');
    Route::post('/addImage', 'Admin\ProductController@addImage')->name('addImage');

    Route::get('/users', 'Admin\MainController@users')->name('users');
    Route::get('/popup_edit_user/{id}', 'Admin\MainController@popup_edit_user')->name('popup_edit_user');
    Route::get('/orders', 'Admin\MainController@orders')->name('orders');
    Route::get('/s_order/{id}', 'Admin\MainController@s_order')->name('s_order');
    Route::get('/query', 'Admin\MainController@query')->name('query');
    Route::get('/view_query/{id}', 'Admin\MainController@view_query')->name('view_query');
    Route::get('/addBanner', 'Admin\BannerController@store')->name('addBanner');
    Route::get('/banner', 'Admin\BannerController@banner')->name('banner');
    Route::get('/AddBanner', 'Admin\BannerController@AddBanner')->name('AddBanner');
    Route::post('/storeBanner', 'Admin\BannerController@store')->name('storeBanner');
    Route::get('/terms', 'Admin\MainController@terms')->name('terms');
    Route::get('/privacy-policy', 'Admin\MainController@privacy')->name('privacy-policy');
    Route::get('/about-us', 'Admin\MainController@about')->name('about-us');
    Route::post('/edit_pages-us', 'Admin\MainController@edit_pages')->name('edit_pages');
    Route::get('/view_payment', 'Admin\MainController@view_payment')->name('view_payment');
    Route::get('/delete_product/{id}', 'Admin\ProductController@delete_product')->name('delete_product');
});
Route::get('changeuserstatus/{id}/{status}', 'Admin\MainController@changeuserstatus')->name('changeuserstatus');
Route::get('changeOrderstatus/{id}/{status}', 'Admin\MainController@changeOrderstatus')->name('changeOrderstatus');
Route::get('changeBannerImage/{id}/{status}', 'Admin\BannerController@changeBannerImage')->name('changeBannerImage');
Route::get('changeCategorystatus/{id}/{status}', 'Admin\CategoryController@changeCategorystatus')->name('changeCategorystatus');
Route::get('changeproductStatus/{id}/{status}', 'Admin\ProductController@changeproductStatus')->name('changeproductStatus');



//Auth::routes();
