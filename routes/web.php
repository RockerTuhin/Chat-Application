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

Route::get('/home', 'HomeController@index')->name('home');

//FOR GROUP CHAT
Route::get('messages','MessageController@fetchMessages');
Route::post('messages','MessageController@sendMessages');
//FOR PRIVATE CHAT
Route::get('private','HomeController@private')->name('private');
Route::get('users','HomeController@users')->name('users');
Route::get('private-messages/{friendId}','MessageController@fetchprivateMessages')->name('private.messages');
Route::post('private-messages/{friendId}','MessageController@sendPrivateMessages')->name('private.messages.store');