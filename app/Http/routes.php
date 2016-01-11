<?php

use App\Controllers;

Route::get('/', 'ProjectController@getIndex');
Route::get('/verified', 'ProjectController@getVerify');

Route::get('/signup', 'ProjectController@getSignUp');
Route::post('/signup', 'ProjectController@postSignUp');

Route::get('/users', 'ProjectController@getUsers');
Route::post('/users', 'ProjectController@postUsers');

Route::get('/deactivate', 'ProjectController@getDeactivate');
Route::post('/deactivate', 'ProjectController@postDeactivate');

Route::get('/login', 'ProjectController@getLogIn');
Route::post('/login', 'ProjectController@postLogIn');

Route::get('/newpost', 'ProjectController@getNew');
Route::post('/newpost', 'ProjectController@postNew');

Route::get('/logout', 'ProjectController@logout');

Route::get('{i}/edit', 'ProjectController@getRepost');
Route::post('{i}/{subject}/edit', 'ProjectController@postRepost');

Route::get('{i}/delete', 'ProjectController@getDelete');

Route::get('{email}/{hash}/verify', 'ProjectController@verify');

Route::get('/forgot', 'ProjectController@getForgot');
Route::post('/forgot', 'ProjectController@postForgot');

Route::get('{email}/{hash}/reset', 'ProjectController@getReset');
Route::post('{email}/{hash}/reset', 'ProjectController@postReset');

Route::get('/accounts', 'ProjectController@getAccounts');
Route::post('/accounts', 'ProjectController@postAccounts');

Route::get('/tiny', 'ProjectController@getTiny');

?>