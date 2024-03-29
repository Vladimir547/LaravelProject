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

Route::get('/parse/onliner_product/{slug}', 'ParserController@getParse');

Route::get('/parse/add/{slug}', 'ParserController@addCatalog');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/parse/onliner_catalog', 'ParserController@getCatalog');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
