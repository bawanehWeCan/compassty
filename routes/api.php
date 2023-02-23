<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IntroductionController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CodeController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\IconController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\PageController;

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



Route::post('sociallogin', [AuthController::class, 'sociallogin']);

Route::post('/otp-check', [AuthController::class, 'check']);

Route::post('/password-otp', [AuthController::class, 'password']);

Route::post('change-password', [AuthController::class, 'changePassword']);

Route::post('/otp-activate-check', [AuthController::class, 'checkPhone']);

Route::post('delete-user/{id}', [AuthController::class, 'delete']);

Route::post('/activate-otp', [AuthController::class, 'activate']);
//Auth
Route::middleware(['auth:api', 'changeLang'])->group(function () {



    Route::post('/user-update', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/update-password', [AuthController::class, 'updatePassword']);

    Route::post('address-create', [AddressController::class, 'save']);

    Route::get('my-addresses', [AddressController::class, 'myAddresses']);
    Route::get('recent-used', [AddressController::class, 'recentAddresses']);

    Route::get('address/{code}', [AddressController::class, 'view']);
});


Route::get('address2/{code}', [AddressController::class, 'view'])->name('login2');


Route::middleware('changeLang')->group(function () {


    //Category
    Route::get('categories', [CategoryController::class, 'list']);
    Route::post('category-create', [CategoryController::class, 'save']);
    Route::get('category/{id}', [CategoryController::class, 'view']);
    Route::get('category/delete/{id}', [CategoryController::class, 'delete']);
    Route::post('category/edit/{id}', [CategoryController::class, 'edit']);




    //Introduction
    Route::get('introductions', [IntroductionController::class, 'list']);
    Route::post('introduction-create', [IntroductionController::class, 'save']);
    Route::get('introduction/{id}', [IntroductionController::class, 'view']);
    Route::get('introduction/delete/{id}', [IntroductionController::class, 'delete']);
    Route::post('introduction/edit/{id}', [IntroductionController::class, 'edit']);
    Route::get('test', [IntroductionController::class, 'test']);


    //Company
    Route::get('companies', [CompanyController::class, 'list']);
    Route::post('company-create', [CompanyController::class, 'save']);
    Route::get('company/{id}', [CompanyController::class, 'view']);
    Route::get('company/delete/{id}', [CompanyController::class, 'delete']);
    Route::post('company/edit/{id}', [CompanyController::class, 'edit']);

    Route::post('companies/search', [CompanyController::class, 'lookfor']);


    //Notification
    Route::get('notifications', [NotificationController::class, 'list']);
    Route::post('notification-create', [NotificationController::class, 'save']);
    Route::get('notification/{id}', [NotificationController::class, 'view']);
    Route::get('notification/delete/{id}', [NotificationController::class, 'delete']);
    Route::post('notification/edit/{id}', [NotificationController::class, 'edit']);



    //Country
    Route::get('countries', [CountryController::class, 'list']);
    Route::post('country-create', [CountryController::class, 'save']);
    Route::get('country/{id}', [CountryController::class, 'view']);
    Route::get('country/delete/{id}', [CountryController::class, 'delete']);
    Route::post('country/edit/{id}', [CountryController::class, 'edit']);


    //City
    Route::get('cities', [CityController::class, 'list']);
    Route::post('city-create', [CityController::class, 'save']);
    Route::get('city/{id}', [CityController::class, 'view']);
    Route::get('city/delete/{id}', [CityController::class, 'delete']);
    Route::post('city/edit/{id}', [CityController::class, 'edit']);

    Route::get('cities/{country_id}', [CityController::class, 'getCitiesByCountry']);
});



//Auth
Route::post('login', [AuthController::class, 'login']);

Route::post('/user-reg', [AuthController::class, 'store']);


Route::post('/otb-check', [AuthController::class, 'check']);

Route::post('/password-otb', [AuthController::class, 'password']);

Route::post('change-password', [AuthController::class, 'changePassword']);








//Order
Route::get('orders', [OrderController::class, 'list']);
Route::post('order-create', [OrderController::class, 'save']);
Route::get('order/{id}', [OrderController::class, 'view']);
Route::get('order/delete/{id}', [OrderController::class, 'delete']);
Route::post('order/edit/{id}', [OrderController::class, 'edit']);



//Address
Route::get('addresses', [AddressController::class, 'list']);


Route::get('address/delete/{id}', [AddressController::class, 'delete']);
Route::post('address/edit/{id}', [AddressController::class, 'edit']);


//Code
Route::post('sell-code', [CodeController::class, 'sellCode']);


//Image
Route::get('images', [ImageController::class, 'list']);
Route::post('image-create', [ImageController::class, 'save']);
Route::get('image/{id}', [ImageController::class, 'view']);
Route::get('image/delete/{id}', [ImageController::class, 'delete']);
Route::post('image/edit/{id}', [ImageController::class, 'edit']);



//Icon
Route::get('icons', [IconController::class, 'list']);
Route::post('icon-create', [IconController::class, 'save']);
Route::get('icon/{id}', [IconController::class, 'view']);
Route::get('icon/delete/{id}', [IconController::class, 'delete']);
Route::post('icon/edit/{id}', [IconController::class, 'edit']);






//Category
Route::get('categories', [CategoryController::class, 'list']);
Route::post('category-create', [CategoryController::class, 'save']);
Route::get('category/{id}', [CategoryController::class, 'view']);
Route::post('category-companies/{id}', [CategoryController::class, 'viewCompanies']);
Route::get('category/delete/{id}', [CategoryController::class, 'delete']);
Route::post('category/edit/{id}', [CategoryController::class, 'edit']);


 //pages

 Route::get('pages', [PageController::class, 'list']);
 Route::post('pages-create', [PageController::class, 'save']);
 Route::get('page/{id}', [PageController::class, 'view']);
 Route::get('page/delete/{id}', [PageController::class, 'delete']);
 Route::post('page/edit/{id}', [PageController::class, 'edit']);
