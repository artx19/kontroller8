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

$routes = [
    '/',
    '/dashboard',
    '/items',
    '/items/edit',
    '/calendar'
];

foreach ($routes as $route) {
    Route::get($route, 'AppController@index');
}

Route::get('/auth', function (){
    return view('auth');
});
