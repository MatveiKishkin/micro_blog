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
    Route::get('/', [BlogPostController::class, 'index']);
    Route::post('/', [BlogPostController::class, 'create']);
    Route::post('/{id}', [BlogPostController::class, 'update']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/', [BlogPostController::class, 'create']);
    });
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
