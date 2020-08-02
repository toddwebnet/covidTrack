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
Route::middleware([
    \App\Http\Middleware\TrackUsage::class
])->group(function () {
    Route::get('/', 'AppController@index');

});
Route::get('/data', 'AppController@data');
Route::get('/data/{formula}', 'AppController@data');
Route::get('/data/{formula}/{source}/{id}', 'AppController@data');

Route::get('/table', 'AppController@table');
Route::get('/table/{source}/{id}', 'AppController@table');

Route::get('/label', 'AppController@label');
Route::get('/label/{source}/{id}', 'AppController@label');
