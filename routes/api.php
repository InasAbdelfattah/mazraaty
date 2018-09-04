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

Route::group(['prefix' => 'v1'], function () {


    // Complate Agent information for agent after activation code is successfully.
    Route::post('user/register', 'Api\V1\RegistrationController@store')->name('user.register');

    // and can login with phone after send activation code successfully.
    Route::post('user/activation', 'Api\V1\LoginController@postActivationCode');

    // forget pass :
    Route::post('password/forgot', 'Api\V1\ForgotPasswordController@getResetTokens');
    Route::post('password/forgot/resend', 'Api\V1\ForgotPasswordController@resendResetPasswordCode');
    Route::post('password/reset', 'Api\V1\ResetPasswordController@reset');
    //end forget pass

    Route::post('/user/login', 'Api\V1\LoginController@login');

    Route::get('cities', 'Api\V1\CityController@index');
    Route::get('categories', 'Api\V1\CategoryController@index');
    Route::get('measurements', 'Api\V1\MeasurementUnitController@index');
    Route::get('products', 'Api\V1\ProductController@index');
    Route::get('offers', 'Api\V1\OfferController@index');
    Route::get('ads', 'Api\V1\AdController@index');
    Route::get('faqs', 'Api\V1\FaqController@index');
    Route::get('general-info', 'Api\V1\SettingController@index');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'v1'], function () {

    Route::get('profile', 'Api\V1\UsersController@profile');

    Route::post('profile/update', 'Api\V1\UsersController@profileUpdate');

    Route::post('password/change', 'Api\V1\UsersController@changePassword');
    
    /**
     * orders
     */
    Route::get('orders/user-orders', 'Api\V1\OrderController@getUserOrders');
    Route::post('orders/pay-app-ratio', 'Api\V1\OrderController@payAppRatio');
    Route::post('orders/save-new-order', 'Api\V1\OrderController@saveOrder');
    Route::post('user/logout', 'Api\V1\UsersController@logout');

    /**
     * @ User Notifications
     */
    Route::get('notifications', 'Api\V1\NotificationsController@getUserNotifications');    
    Route::get('setSeen', 'Api\V1\NotificationsController@seeNotif');
    Route::get('notifs-count', 'Api\V1\NotificationsController@countNotifs');
    Route::post('notification/delete', 'Api\V1\NotificationsController@delete');

});
