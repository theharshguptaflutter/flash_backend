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
Route::get('/', function () {
    return "Test";
});

// Auth::routes();

define('ADMIN_PREFIX', 'admin');
Route::group(['namespace' => 'Admin', 'prefix' => ADMIN_PREFIX], function () {
    Route::get('not-found','HomeController@pageNotFound')->name('notfound');
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
    Route::group(['middleware' => ['web', 'guest']], function () {
        Route::post('admin-login', 'Auth\LoginController@LoginSubmit');
    });
    Route::group(['middleware' => ['web', 'auth','AdminMiddleware']], function () {
        Route::get('dashboard','HomeController@dashboard')->name('dashboard');
        Route::get('logout','Auth\LoginController@logout');
        Route::get('admin-profile','HomeController@adminProfile')->name('profile');
        Route::post('Profile-Save','HomeController@adminProfileSave');
        Route::post('Change-Password','HomeController@adminChangePassword');
        // all user list
        Route::get('user-list','UserManagementController@userList')->name('user-list');
        Route::get('user-view-{id}','UserManagementController@userDetails')->name('user-view');



        Route::get('driver-list','UserManagementController@driverList')->name('driver-list');
        Route::get('driver-awaiting-list','UserManagementController@driverAwaitingList')->name('driver-awaiting-list');
        Route::get('driver-view-{id}', 'UserManagementController@driverDetails')->name('driver-view');
        Route::get('driver-awaiting-view-{id}', 'UserManagementController@driverAwaitingView')->name('driver-awaiting-view');
        Route::post('driver-update-{id}','UserManagementController@driverAwaitingUpdate')->name('driver-update');
        Route::post('status-change','UserManagementController@statusChange')->name('status_change');

        Route::get('car-make-list','UserManagementController@carMakeList')->name('car-make-list');
        Route::get('car-make-add','UserManagementController@carMakeAdd')->name('car-make-add');
        Route::post('car-make-store','UserManagementController@carMakeStore')->name('car-make-store');
        Route::get('car-make-view-{id}', 'UserManagementController@carMakeView')->name('car-make-view');
        Route::post('car-make-update-{id}','UserManagementController@carMakeUpdate')->name('car-make-update');
        Route::get('car-make-delete-{id}','UserManagementController@carMakeDelete')->name('car-make-delete');

        Route::get('car-model-list','UserManagementController@carModelList')->name('car-model-list');
        Route::get('car-model-add','UserManagementController@carModelAdd')->name('car-model-add');
        Route::post('car-model-store','UserManagementController@carModelStore')->name('car-model-store');
        Route::get('car-model-view-{id}', 'UserManagementController@carModelView')->name('car-model-view');
        Route::post('car-model-update-{id}','UserManagementController@carModelUpdate')->name('car-model-update');
        Route::get('car-model-delete-{id}','UserManagementController@carModelDelete')->name('car-model-delete');

        Route::get('approval-amount-list','UserManagementController@approvalAmountList')->name('approval-amount-list');
        Route::get('approval-amount-view-{id}', 'UserManagementController@approvalAmountView')->name('approval-amount-view');
        Route::post('approval-amount-update-{id}','UserManagementController@approvalAmountUpdate')->name('approval-amount-update');

    });
});

Route::get('driver-term-condition','PDFController@driverTermCondition')->name('driver-term-condition');
Route::get('passenger-term-condition','PDFController@passengerTermCondition')->name('passenger-term-condition');
Route::get('privacy-policy','PDFController@privacyPolicy')->name('privacy-policy');
Route::get('contact-us','PDFController@contactUs')->name('contact-us');

Route::get('/download-driver-pdf', 'PDFController@driverPdf')->name('download-driver-pdf');
Route::post('/paygate/response', 'PaymentController@pg_response')->name('payment_response');
Route::post('/notify', 'PaymentController@notify')->name('payment_notify');

Route::post('/paygate/passanger-response', 'PaymentController@passangerPgResponse')->name('passanger_payment_response');
Route::post('/notify-passanger', 'PaymentController@notifyPassangerPayment')->name('notify_passanger_payment');



