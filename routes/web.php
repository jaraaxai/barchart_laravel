<?php
use App\Http\Middleware\CheckDupli;
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

Route::get('/', 'QuoteController@index');

Route::get('create/{keyword}', ['as' => 'quotes.create', 'uses' => 'QuoteController@create']);
Route::post('store', ['as' => 'quotes.store', 'uses' => 'QuoteController@store'])->middleware(CheckDupli::class);

Route::get('edit/{id}', ['as' => 'quotes.edit', 'uses' => 'QuoteController@edit']);
Route::PATCH('update/{id}', ['as' => 'quotes.update', 'uses' => 'QuoteController@update']);

Route::post('/search', 'QuoteController@search');
Route::DELETE('delete/{id}', ['as' => 'quotes.delete', 'uses' => 'QuoteController@destroy']);

