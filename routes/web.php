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

Route::group(['domain' => '{user:domain}.'.config('app.short_url'), 'as' => 'tenant.'], function () {
    Route::get('/', 'TenantController@show')->name('show');
});

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/invitation/{user}', 'TenantController@invitation')->name('invitation');

Route::get('/password', 'Auth\PasswordController@create')->name('password.create');

Route::post('/password', 'Auth\PasswordController@store')->name('password.store');

Route::group(['as' => 'admin.', 'namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('tenants/{tenant}/suspend', 'TenantController@suspend')->name('tenants.suspend');

    Route::resource('tenants', 'TenantController');

    Route::resource('users', 'UserController');

    Route::resource('roles', 'RoleController');

    Route::get('profile', 'ProfileController@edit')->name('profile.edit');

    Route::put('profile', 'ProfileController@update')->name('profile.update');
});
