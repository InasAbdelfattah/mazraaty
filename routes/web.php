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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'administrator'], function () {

    Route::get('/', 'Admin\LoginController@login')->name('admin');
    Route::get('/login', 'Admin\LoginController@login')->name('admin.login');
    Route::post('/login', 'Admin\LoginController@postLogin')->name('admin.postLogin');


    // Password Reset Routes...

    Route::get('password/reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('administrator.password.request');
    Route::post('password/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('administrator.password.email');
    Route::get('password/reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm')->name('administrator.password.reset.token');
    Route::post('password/reset', 'Admin\Auth\ResetPasswordController@reset');

});

//Route::get('lang/{language}', ['as' => 'lang.switch', 'uses' => 'Api\V1\LanguageController@switchLang']);

Route::group(['prefix' => 'administrator', 'middleware' => 'admin'], function () {

    Route::get('/', 'Admin\HomeController@index')->name('home');
    Route::get('/home', 'Admin\HomeController@index')->name('admin.home');

    Route::resource('abilities', 'Admin\AbilitiesController');
    Route::post('abilities_mass_destroy', ['uses' => 'Admin\AbilitiesController@massDestroy', 'as' => 'abilities.mass_destroy']);
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    
    Route::get('profile/{id}', ['uses' => 'Admin\UsersController@profile', 'as' => 'users.profile']);
    
    Route::get('profile/edit/{id}', ['uses' => 'Admin\UsersController@editProfile', 'as' => 'users.editProfile']);
    
    Route::put('profile/update/{id}', ['uses' => 'Admin\UsersController@updateProfile', 'as' => 'users.updateProfile']);

    Route::resource('users', 'Admin\UsersController');

    Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);

    Route::get('app_users', ['uses' => 'Admin\UsersController@getUsers', 'as' => 'users.app_users']);
    Route::get('suspended_users', ['uses' => 'Admin\UsersController@getSuspendedUsers', 'as' => 'users.suspended_users']);
    Route::get('suspended_admins', ['uses' => 'Admin\UsersController@getSuspendedAdmins', 'as' => 'users.suspended_admins']);

    Route::post('role/delete/group', 'Admin\RolesController@groupDelete')->name('roles.group.delete');

    Route::post('user/activate', 'Admin\UsersController@activateProvider')->name('user.activateProvider');
    Route::post('user/suspend', 'Admin\UsersController@suspendUser')->name('user.suspend');
    Route::post('user/delete/group', 'Admin\UsersController@groupDelete')->name('users.group.delete');
    Route::post('user/suspend/group', 'Admin\UsersController@groupSuspend')->name('users.group.suspend');

    Route::post('role/delete', 'Admin\RolesController@delete')->name('role.delete');

    /**
     * @@ Manage Categories Routes.
     */
    Route::post('category/activate-category', 'Admin\CategoriesController@activateCategory')->name('category.activateCategory');
    Route::get('sub-categories', 'Admin\CategoriesController@getSubCategories')->name('subcategories');
    Route::post('category/delete/group', 'Admin\CategoriesController@groupDelete')->name('categories.group.delete');


    Route::resource('categories', 'Admin\CategoriesController');

    Route::post('product/activate-product', 'Admin\ProductController@activateProduct')->name('product.activateProduct');
    Route::resource('products', 'Admin\ProductController');

//sliders
    Route::get('ads/createAd', 'Admin\SliderController@createAd')->name('sliders.createAd');
    Route::get('ads/editAd/{id}', 'Admin\SliderController@editAd')->name('sliders.editAd');
    Route::get('ads', 'Admin\SliderController@getAds')->name('sliders.ads');

    Route::post('slider/delete', 'Admin\SliderController@delete')->name('slider.delete');
    Route::post('slider/delete/group', 'Admin\SliderController@groupDelete')->name('sliders.group.delete');
    Route::resource('sliders', 'Admin\SliderController');

    Route::post('contactus/reply/{id}', 'Admin\SupportsController@reply')->name('support.reply');
    Route::get('contactus', 'Admin\SupportsController@index')->name('support.index');
    Route::get('contactus/{id}', 'Admin\SupportsController@show')->name('support.show');
    Route::post('contactus', 'Admin\SupportsController@delete')->name('support.delete');

    /**
     * Cities Routes
     */
    Route::post('area/activate-area', 'Admin\CitiesController@activateArea')->name('city.activateArea');
    Route::post('city/delete/group', 'Admin\CitiesController@groupDelete')->name('cities.group.delete');
    Route::resource('cities', 'Admin\CitiesController');
    
    /**
     * @ orders Routes
     */
    Route::get('orders/export-excel', 'Admin\OrderController@getExport')->name('orders.getExport');
    Route::get('orders/export-user-excel', 'Admin\OrderController@getExportUsers')->name('orders.getExportUsers');
    Route::post('orders/delete/group', 'Admin\OrderController@groupDelete')->name('orders.group.delete');
    Route::post('orders/delete', 'Admin\OrderController@delete')->name('orders.delete');
    Route::get('orders/search', 'Admin\OrderController@search')->name('orders.search');
    Route::get('orders/financial-reports', 'Admin\OrderController@getFinancialReports')->name('orders.financial_reports');
    Route::get('orders/search_financial_reports', 'Admin\OrderController@searchFinancialReports')->name('orders.search_reports');
    
    // users reports
    Route::get('orders/financial-users_reports', 'Admin\OrderController@getUsersReports')->name('orders.users_reports');
    Route::get('orders/search_users_reports', 'Admin\OrderController@searchUsersReports')->name('orders.search_users_reports');
    Route::post('orders/confirm-order', 'Admin\OrderController@confirmOrder')->name('orders.confirmOrder');    
    Route::resource('orders', 'Admin\OrderController');
    /**
     * @ Setting Routes
     */
    Route::get('/settings/aboutus', 'Admin\SettingsController@aboutus')->name('settings.aboutus');
    Route::get('/settings/terms', 'Admin\SettingsController@terms')->name('settings.terms');
    Route::get('/settings/edu_terms', 'Admin\SettingsController@eduTerms')->name('settings.edu_terms');
    Route::get('/settings/site', 'Admin\SettingsController@site')->name('settings.site');
    Route::get('/settings/socials/links','Admin\SettingsController@socialLinks')->name('settings.socials');
    Route::post('/settings', 'Admin\SettingsController@store')->name('administrator.settings.store');

    // notifications
    Route::group(['prefix' => 'notifications'], function () {

        // show all notifications
        Route::get('/', [
            'uses' => 'Admin\NotificationController@getIndex',
            'as' => 'notifs'
        ]);

        Route::get('/new', [
            'uses' => 'Admin\NotificationController@getNotif',
            'as' => 'new-notif'
        ]);

        Route::post('/send', [
            'uses' => 'Admin\NotificationController@send',
            'as' => 'notif-send'
        ]);
        
        Route::post('/delete', 'Admin\NotificationController@delete')->name('notifs.delete');
        Route::get('/show/{id}', 'Admin\NotificationController@show')->name('notifs.show');

    });
    Route::post('/logout', 'Admin\LoginController@logout')->name('administrator.logout');
});

Route::get('roles', function () {

    $user = auth()->user();
//    $user->retract('admin');
    $user->assign('owner');
    Bouncer::allow('owner')->everything();

    $user->allow('users_manage');

    //Bouncer::allow('admin')->to('users_manage');
    //Bouncer::allow($user)->toOwnEverything();
});