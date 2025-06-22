<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\NoteController;
use App\Http\Controllers\API\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Public Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    //Auth Routes
    Route::controller(AuthController::class)->name('auth.')->group(function () {
        Route::post('logout', 'logout')->name('logout');
        Route::put('password', 'updatePassword')->name('password.update');
    });

    // Notes Extra Routes
    Route::prefix('notes')->controller(NoteController::class)->name('notes.')->group( function () {
        Route::get('search', 'search')->name('search');
        Route::get('trash', 'trash')->name('trash');
        Route::put('{slug}/restore', 'restore')->name('restore');
        Route::delete('{slug}/soft-delete', 'softDelete')->name('soft-delete');
        Route::put('{slug}/toggle-pin', 'togglePin')->name('toggle-pin');
    });

    // Notes Resource Routes (RESTful)
    Route::apiResource('notes', NoteController::class);

    // Profile Routes
    Route::prefix('profile')->controller(ProfileController::class)->name('profile.')->group(function () {
        Route::get('/', 'show')->name('show');
        Route::put('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });
});

