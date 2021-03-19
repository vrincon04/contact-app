<?php

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes([
    'register' => false
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'contact'], function() {
        Route::get('/', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
        Route::get('/{id}', [App\Http\Controllers\ContactController::class, 'show'])->name('contact.show');
    });

    Route::group(['prefix' => 'file'], function() {
        Route::get('/', [App\Http\Controllers\FileController::class, 'index'])->name('file.index');
        Route::get('/{id}', [App\Http\Controllers\FileController::class, 'show'])->name('file.show');
        Route::post('upload', [App\Http\Controllers\FileController::class, 'upload'])->name('file.upload');
    });
});
