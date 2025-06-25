<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('notes.index')
        : view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'prevent-back'])->name('dashboard');

Route::middleware(['prevent-back', 'auth'])->group(function () {

    // Profile Routes
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });


    // Notes Extra Routes
    Route::prefix('notes')->name('notes.')->controller(NoteController::class)->group(function () {
        Route::get('trash', 'trash')->name('trash');
        Route::get('search', 'search')->name('search');
        Route::put('{slug}/toggle-pin', 'togglePin')->name('toggle-pin');
        Route::put('{slug}/restore', 'restore')->name('restore');
        Route::delete('{slug}/soft-delete', 'softDelete')->name('soft-delete');
    });

    // Notes Resource Routes
    Route::resource('notes', NoteController::class);
});

// Google Auth Routes
Route::prefix('auth/google/')->name('auth.google.')->controller(GoogleAuthController::class)->group(function () {
    Route::get('redirect', 'redirect')->name('redirect');
    Route::get('callback', 'callBack')->name('callback');
});

require __DIR__.'/auth.php';
