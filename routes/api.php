<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->group(function () {
    Route::prefix('v1')->namespace('V1')->group(function () {
      Route::group(['middleware' => 'guest'], function () {
        Route::post('mail-send', 'AuthController@mailSend')->name('mail-send');
        Route::get('phone-code', 'AuthController@getPhoneCode'); 
        Route::get('car-make', 'AuthController@fetchCarMakeRecord'); 
        Route::get('car-model', 'AuthController@fetchCarModelRecord'); 
        Route::post('/customer-register', 'AuthController@customerRegister');
        Route::post('/login', 'AuthController@login');

	Route::get('firebase', 'AuthController@sendNotifications');
	Route::get('driverFirebase', 'AuthController@sendDriverNotifications');

        Route::post('/verify-phone', 'AuthController@verifyPhone');
        Route::post('/verify-otp', 'AuthController@otpVerify');
        Route::post('/forgot-password', 'AuthController@forgotPassword');
        Route::post('/resend-otp', 'AuthController@resendOTP');
        Route::post('/reset-password', 'AuthController@resetPassword');

        // Driver registration steps
        Route::post('/driver-registration-step-one', 'AuthController@driverRegisterStepOne');
        Route::post('/driver-registration-step-two', 'AuthController@driverRegisterStepTwo');
        Route::post('/driver-registration-step-three', 'AuthController@driverRegisterStepThree');
        Route::post('/driver-registration-step-four', 'AuthController@driverRegisterStepFour');
        Route::post('/driver-registration-step-five', 'AuthController@driverRegisterStepFive');

        //Route::get('pay-gate-data', 'AuthController@payGateData');
        //Route::get('get-plan-detail', 'AuthController@fetchPlanDetail');

        Route::post('logout', 'AuthController@logout');

        // Inspection
        Route::post('/inspection-login', 'AuthController@inspectionLogin');
        Route::post('/inspection-forgot-password', 'AuthController@forgotPasswordForInspection');
        Route::post('/otp-verify-forgot-password', 'AuthController@otpVerifyForInspection');
        Route::post('/resend-otp-forgot-password', 'AuthController@resendOTPForInspection');
        Route::post('/reset-password-forgot-password', 'AuthController@resetPasswordForInspection');

        Route::get('get-car-type', 'AuthController@carCategory'); 
        Route::get('get-car-type-details', 'AuthController@carCategoryDetails');
      });

      // Passenger 
      Route::group(['middleware' => ['PassengerMiddleware','auth:api']], function () {
        Route::post('update-passenger-profile', 'PassengerController@updateProfile'); 
        Route::post('update-passenger-password', 'PassengerController@changePassengerPassword'); 
        Route::post('passenger-current-location-add', 'PassengerController@addCurrentLocation'); 
        Route::post('passenger-ride-detail', 'PassengerController@addRideInfoDetails');
   	Route::post('passenger-calculate-billing', 'PassengerController@calculateBilling');

	Route::post('customer-pay-through-card', 'PassengerController@payGateInitiate');
	Route::post('customer-paygate-response', 'PassengerController@pg_response')->name('customer_payment_response');

        Route::post('passenger-add-new-place', 'PassengerController@addNewPlace');
        Route::post('passenger-edit-place', 'PassengerController@editPlace');
        Route::post('passenger-delete-place', 'PassengerController@deletePlace');
        Route::get('passenger-new-place-list', 'PassengerController@newPlaceList');
	Route::get('notificationList', 'PassengerController@notificationList'); 
        Route::post('add-passenger-payment-detail', 'PassengerController@addPaymentDetails');
        Route::get('passenger-ride-listing', 'RideController@passengerRideListing');
	Route::get('get-passanger-ride-history', 'PassengerController@fetchDriverRideHistory');
      });

      // Driver
      Route::group(['middleware' => ['DriverMiddleware','auth:api']], function () {
        Route::post('update-driver-profile', 'DriverController@updateProfile');
        Route::post('update-driver-password', 'DriverController@changeDriverPassword');
        Route::get('get-ride-history', 'DriverController@fetchDriverRideHistory');
	// Route::get('get-ride-detail', 'DriverController@fetchDriverCarDetails');
        Route::post('update-ride-details', 'DriverController@updateDriverCarDetails');
        Route::post('online-status-check', 'DriverController@onlineStatusCheck');
        Route::post('covid-status-check', 'DriverController@covidStatusCheck');
        Route::get('get-past-ride-list', 'DriverController@pastRideList');
	Route::get('passenger-billing', 'DriverController@passengerBilling');

	Route::post('driver-calculate-billing', 'DriverController@calculateBilling');

        Route::post('add-passenger-ride-rating', 'DriverController@addRatingForPassenger');
        Route::get('rate-passenger-list', 'DriverController@rateCustomerList');
        Route::post('my-earning-list', 'DriverController@myEarningRecord');
	Route::post('give-rating-to-customer', 'DriverController@giveRatingToCustomer');
	Route::get('get-customer-for-rating', 'DriverController@getCustomerForRating');

        // @todo - not use for now
        // Route::post('ride-accept-by-driver', 'DriverController@rideAcceptForPassenger');
        // Route::post('ride-reject-by-driver', 'DriverController@rideRejectForPassenger');

        Route::post('add-bank-details', 'DriverController@addBankRecord');
        Route::get('bank-list', 'DriverController@bankDetailList');
        Route::post('set-as-primary', 'DriverController@setAsPrimary');
        Route::post('delete-bank-record', 'DriverController@deleteBankRecord');
        Route::post('driver-delete','DriverController@driverDelete');
	Route::get('today-total-earning', 'DriverController@todayEarning');
	Route::get('driver-earning', 'DriverController@driverEarning');
	Route::post('complete-payment', 'DriverController@completeRide');

        Route::get('get-current-step', 'RideHistoryController@getCurrentRideStep');
        Route::post('set-current-step', 'RideHistoryController@store');
      });

      //Inspector
      Route::group(['middleware' => ['InspectorMiddleware','auth:api']], function () {
        Route::post('update-inspection-profile', 'InspectorController@updateInspectorProfile');
        Route::post('update-inspection-password', 'InspectorController@changeInspectionPassword');
        Route::post('verify-driver-document', 'InspectorController@verifyDriverDocuments');
        Route::post('verify-driver-details', 'InspectorController@verifyDriverDetails');
        Route::post('verify-important-document', 'InspectorController@verifyImportantDocumets');
        Route::post('get-driver-details-list', 'InspectorController@getDriverDetailsList');
        Route::get('get-all-inspection-list', 'InspectorController@allInspectionList');
        Route::post('view-inspection','InspectorController@viewInspectionDetails');
        Route::post('download-inspection-report-pdf', 'InspectorController@downloadInspectionReport')->name('download-inspection-report-pdf');
        Route::post('update-manage-inspection-driver-details', 'InspectorController@updateManageInspectionDriverDetail');
        // Route::post('verify-checkout-payment', 'InspectorController@verifyCheckOutPayment');
        Route::get('pay-gate-initiate', 'InspectorController@payGateInitiate');
      });

      // Common for driver and passenger 
      Route::group(['middleware' => ['CommonMiddleware','auth:api']], function () {
        Route::post('driver-review-add', 'RideController@addDriverReview');
      });
   });
});