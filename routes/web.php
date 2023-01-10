<?php

use App\Http\Controllers\BlogPostController;
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

Route::group(['as' => 'main.'], function () {

    /**
     * Главная страница.
     */
    Route::get('/', function () {
        return view('welcome');
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/blog', [BlogPostController::class, 'index']);
    });

});