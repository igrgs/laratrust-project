<?php

use Illuminate\Support\Facades\Auth;
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

$administracao = [
    'prefix' => 'administracao',
    'domain' => '',
    'middleware' => ['auth', 'role:superadministrador|developer,administracao'],
    'as' => 'administracao.',
    'namespace' => 'Administracao',
];

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



//Route::resource('users', 'UserController')->middleware(['auth', 'role:superadministrador,administracao']);


// Route::prefix('administracao')->namespace('Administracao')->group(function () {
// });



Route::group(['prefix' => 'administracao', 'middleware' => ['auth', 'role:superadministrador|administrador|developer,administracao'], 'as' => 'administracao.', 'namespace' => 'Administracao'], function () {
    Route::resource('users', 'UserController');
});
