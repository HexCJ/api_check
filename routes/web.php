<?php

use App\Http\Controllers\UserViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => '/'], function () {
    Route::get('/', [UserViewController::class, 'index'])->name('user');
    Route::post('/users/sync', [UserViewController::class, 'sync'])->name('users.sync');
    Route::get('/user/create', [UserViewController::class, 'create'])->name('user.create');
    Route::post('/user/store', [UserViewController::class, 'store'])->name('user.store');
    Route::get('/user/edit/{id}', [UserViewController::class, 'edit'])->name('user.edit');
    Route::put('/update-user/{id}', [UserViewController::class, 'update'])->name('user.update');
    Route::delete('/users/delete/{id}', [UserViewController::class, 'destroy'])->name('user.delete');

});