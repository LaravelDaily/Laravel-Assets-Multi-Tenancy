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

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/invitation/{user}', 'TenantController@invitation')->name('invitation');

Route::get('/password', 'Auth\PasswordController@create')->name('password.create');

Route::post('/password', 'Auth\PasswordController@store')->name('password.store');

Route::domain('{user:domain}.'.config('app.short_url'))->group(function () {
    Route::get('/', 'TenantController@show')->name('tenant');
});

Route::group(['as' => 'admin.', 'namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::resource('tenants', 'TenantController');
});
