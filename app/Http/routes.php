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

Route::get('admin', 'AdministratorController@index' );
Route::post('admin/login', ['middleware' => 'csrf', 'uses'=>'AdministratorController@postLogin'] );

Route::get('admin/logout', ['middleware' => 'authuser', 'uses'=>'AdministratorController@logout']);

Route::get('admin/catlog/addnew', ['middleware' => 'authuser', 'uses'=>'CatController@create']);
Route::post('admin/catlog/addcatlog', ['middleware' => ['authuser', 'csrf'], 'uses'=>'CatController@store' ] );
Route::post('admin/catlog/updateCatlog/{id}', ['middleware' => ['authuser', 'csrf'], 'uses'=>'CatController@update'] );
Route::get('admin/catlog/list', ['middleware' => 'authuser', 'uses'=>'CatController@show']);

Route::get('admin/catlog/getcatloglist', ['middleware' => 'authuser', 'uses'=>'CatController@getlist']);

Route::get('admin/catlog/edit/{id}', ['middleware' => 'authuser', 'uses'=>'CatController@edit']);
Route::get('admin/catlog/delete/{id}/{token}', ['middleware' => 'authuser', 'uses'=>'CatController@destroy']);

Route::get('admin/subcatlog/addnew', ['middleware' => 'authuser', 'uses'=>'SubCatlogController@create']);
Route::post('admin/subcatlog/addcatlog', ['middleware' => ['authuser', 'csrf'], 'uses'=>'SubCatlogController@store' ] );
Route::post('admin/subcatlog/updateCatlog/{id}', ['middleware' => ['authuser', 'csrf'], 'uses'=>'SubCatlogController@update'] );
Route::get('admin/subcatlog/list', ['middleware' => 'authuser', 'uses'=>'SubCatlogController@show']);

Route::get('admin/subcatlog/getcatloglist', ['middleware' => 'authuser', 'uses'=>'SubCatlogController@getlist']);

Route::get('admin/subcatlog/edit/{id}', ['middleware' => 'authuser', 'uses'=>'SubCatlogController@edit']);
Route::get('admin/subcatlog/delete/{id}/{token}', ['middleware' => 'authuser', 'uses'=>'SubCatlogController@destroy']);

