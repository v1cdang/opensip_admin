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
Route::get('getCallerIds/{prefix}/{phonenumber}/{ipaddress}', 'CalleridController@index');
Route::get('checkRoutes/{prefix}/{phonenumber}', 'RoutesController@index');
Route::get('setAllowedCountries', 'CustomerController@index');
Route::get('setAllowedCountries/{prefix}', 'CustomerController@showAllowedCountriesForm');
Route::post('setAllowedCountriesSubmit','CustomerController@setAllowedCountries');
Route::get('checkLRN/{prefix}/{phonenumber}','LrnController@index');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
