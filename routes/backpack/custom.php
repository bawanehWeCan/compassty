<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('country', 'CountryCrudController');
    Route::crud('city', 'CityCrudController');
    Route::crud('introduction', 'IntroductionCrudController');
    Route::crud('category', 'CategoryCrudController');
    Route::crud('icon', 'IconCrudController');
    Route::crud('company', 'CompanyCrudController');
    Route::crud('company-images', 'CompanyImagesCrudController');
    Route::crud('address', 'AddressCrudController');
    Route::crud('code', 'CodeCrudController');
    Route::crud('address-images', 'AddressImagesCrudController');
}); // this should be the absolute last line of this file