<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin', 'AdministratorController@index');

Route::post('admin/login', 'AdministratorController@postLogin');

Route::get('admin/logout', 'AdministratorController@logout');

Route::get('admin/catlog/addnew', 'CatController@create');
Route::post('admin/catlog/addcatlog', 'CatController@store');
Route::post('admin/catlog/updateCatlog/{id}', 'CatController@update');
Route::get('admin/catlog/list', 'CatController@show');

Route::get('admin/catlog/getcatloglist', 'CatController@getlist');

Route::get('admin/catlog/edit/{id}', 'CatController@edit');
Route::get('admin/catlog/delete/{id}/{token}', 'CatController@destroy');

