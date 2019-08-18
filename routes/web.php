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

Route::get('/', 'LatestRatesController@index')->name('latest');
Route::get('/fromto/{from}/{to}', 'LatestRatesController@fromto')->name('latest');
Route::get('/historical', 'HistoricalRatesController@setDefault')->name('historical');
Route::post('/historical', 'HistoricalRatesController@index')->name('historical');

Route::get('/wallet', 'WalletsController@index');
Route::put('/wallet', 'WalletsController@top_up');

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::resource('/offers', 'OffersController');
Route::post('/offers/{offer}/buy', 'OffersController@buy')->name('offers.buy');
