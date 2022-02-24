<?php



use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/threads', 'ThreadsController@index');
Route::get('/threads/create', 'ThreadsController@create')->middleware('auth');;
Route::post('/threads', 'ThreadsController@store')->middleware('auth');
//Route::get('/threads/{thread}', 'ThreadsController@show');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
//Route::resource('threads', ThreadsController::class);
//Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store')->middleware('auth');
Route::get('threads/{channel}','ThreadsController@index');


Route::post('/replies/{reply}/favorites', 'FavouritesController@store')->middleware('auth');
Route::delete('/replies/{reply}','RepliesController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');