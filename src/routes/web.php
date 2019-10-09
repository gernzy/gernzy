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

// Password Reset Route
Route::get(
    'password/reset/{token}',
    'ResetPasswordController@showResetForm'
)
    ->name('password.reset.token');
