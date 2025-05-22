<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController as AuthController;
use App\Http\Controllers\API\NoteController as NoteController;
use App\Http\Controllers\API\ProfileController as ProfileController;

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


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('change-password', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::resource('note', NoteController::class);
    Route::get('trash/notes', [NoteController::class, 'trash'])->name('notes.trash');
    Route::get('restore/note/{id}', [NoteController::class, 'restore'])->name('note.restore');
    Route::get('delete/note/{id}', [NoteController::class, 'delete'])->name('note.delete');
    Route::get('search/note/', [NoteController::class, 'search'])->name('note.search');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
