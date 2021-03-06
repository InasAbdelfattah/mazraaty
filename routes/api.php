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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/activation-code-api/{phone}', 'Api\V1\ForgotPasswordController@getActivationCodeApi');

Route::group(['prefix' => 'v1'], function () {

    // Complate Agent information for agent after activation code is successfully.
    Route::post('user/register', 'Api\V1\RegistrationController@store')->name('user.register');

    // and can login with phone after send activation code successfully.
    Route::post('user/activation', 'Api\V1\LoginController@postActivationCode');

    // forget pass :
    Route::post('password/forgot', 'Api\V1\ForgotPasswordController@getResetTokens');
    Route::post('password/forgot/resend', 'Api\V1\ForgotPasswordController@resendResetPasswordCode');
    // After arrive reset code send to check is true.
    Route::post('check-code', 'Api\V1\ResetPasswordController@check');
    Route::post('password/reset', 'Api\V1\ResetPasswordController@reset');
    //end forget pass
    Route::post('/user/login', 'Api\V1\LoginController@login');


    Route::get('cities', 'Api\V1\CityController@index');
    Route::get('all-cities', 'Api\V1\CityController@getAllCities');

    Route::get('categories', 'Api\V1\CategoryController@index');
    Route::get('measurements', 'Api\V1\MeasurementUnitController@index');
    Route::get('products', 'Api\V1\ProductController@index');
    Route::get('product-details', 'Api\V1\ProductController@details');
    Route::get('offers', 'Api\V1\OfferController@index');
    Route::get('offer-details', 'Api\V1\OfferController@details');
    Route::get('ads', 'Api\V1\AdController@index');
    Route::get('faqs', 'Api\V1\FaqController@index');
    Route::get('general-info', 'Api\V1\SettingController@index');

    Route::get('orders/getBasket', 'Api\V1\OrderController@getBasket');
    Route::post('orders/saveBasket', 'Api\V1\OrderController@saveBasket');
    Route::post('orders/update-item', 'Api\V1\OrderController@updateBasket');
    Route::post('orders/delete-item', 'Api\V1\OrderController@deleteItem');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {

    Route::get('profile', 'Api\V1\UsersController@profile');
    Route::post('renew-device', 'Api\V1\LoginController@renewPlayerId');
    Route::get('user-addresses', 'Api\V1\UsersController@getUserAddresses');
    Route::get('delete-address', 'Api\V1\UsersController@deleteAddress');

    Route::post('create-address', 'Api\V1\UsersController@createAddress');
    Route::post('update-address', 'Api\V1\UsersController@updateAddress');
    
    Route::post('profile/update', 'Api\V1\UsersController@profileUpdate');

    Route::post('password/change', 'Api\V1\UsersController@changePassword');
    
    /**
     * orders
     */

    Route::get('orders/user-recent-order', 'Api\V1\OrderController@getUserRecentOrder');
    Route::get('orders/user-orders', 'Api\V1\OrderController@getUserOrders');
    Route::get('orders/user-finished-orders', 'Api\V1\OrderController@getUserFinishedOrders');
    Route::get('order-details', 'Api\V1\OrderController@getOrderDetails');
    Route::post('orders/save-new-order', 'Api\V1\OrderController@saveOrder');
    Route::post('user/logout', 'Api\V1\UsersController@logout');

    /**
     * @ User Notifications
     */
    Route::get('notifications', 'Api\V1\NotificationsController@getUserNotifications');    
    Route::get('setSeen', 'Api\V1\NotificationsController@seeNotif');
    Route::get('notifications-count', 'Api\V1\NotificationsController@countNotifs');
    Route::post('delete-notification', 'Api\V1\NotificationsController@delete');

    Route::post('vote-for-city', 'Api\V1\CityController@voteForCity');

});
