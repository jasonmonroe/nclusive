<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::get('/',               'nclusive@index');
Route::get('create',          'nclusive@create');
Route::get('show/{id}',       'nclusive@show');
Route::get('edit/{id}',       'nclusive@edit');
Route::get('destroy/{id}',    'nclusive@destroy');
Route::post('update',         'nclusive@update');
Route::post('store',          'nclusive@store');
Route::get('search',          'nclusive@search');
Route::get('auto',            'nclusive@auto');
Route::any('search_results',  'nclusive@search_results');
Route::post('fetch/{mode}',   'nclusive@fetch');

Route::model('profile_model', 'profile_model');