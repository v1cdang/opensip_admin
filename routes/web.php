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
    return view('welcome');
});
Route::get('addCredit', 'AccountsController@index')->middleware('auth');
Route::get('addCredit/{prefix}', 'AccountsController@addCreditForm');
Route::post('addCreditforPrefix', 'AccountsController@updateCredit');
Route::get('creditHistory', 'CreditsController@index');
Route::get('creditHistory/{prefix}', 'CreditsController@viewPrefixCreditHistory');
Route::get('getCallerIds/{prefix}/{ipaddress}', 'CalleridController@index');
Route::get('getAllowedCallerID/{prefix}/{callerid}','CalleridController@getAllowedCallerId');

Route::get('checkRoutes/{prefix}/{phonenumber}', 'RoutesController@index');
Route::get('getCountry/{phonenumber}', 'RoutesController@getCountry');

Route::get('setAllowedCountries', 'CustomerController@index');
Route::get('setAllowedCountries/{prefix}', 'CustomerController@showAllowedCountriesForm');
Route::post('setAllowedCountriesSubmit','CustomerController@setAllowedCountries');
Route::get('checkLRN/{prefix}/{phonenumber}','LrnController@index');

Route::get('addCustomerForm', 'CustomerController@addCustomerForm');
Route::post('addCustomer', 'CustomerController@addCustomer');
Route::get('setCustomerRatesForm', 'CustomerController@setCustomerRatesForm');
Route::post('setCustomerRates', 'CustomerController@setCustomerRates');
Route::get('getChildExtension/{prefix}', 'CustomerController@getChildExtension');
Route::get('getCustomerDID/{prefix}', 'CustomerController@getCustomerDID');

Route::get('addDID','CustomerController@addDID');
Route::post('addDIDtoExt','CustomerController@addDIDtoExt');

Route::get('addExtensionsForm', 'CustomerController@addExtensionForm');
Route::post('addExtension', 'CustomerController@addExtension');

Route::get('getCurrentClientSummary', 'ReportsController@getCurrentClientSummary');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
