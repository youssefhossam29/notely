<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NoteController;
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

Route::get('/', function () {
    if(Auth::user()){
        return redirect()->route('my.notes');
    }else{
        return view('welcome');
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my/notes', [NoteController::class, 'index'])->name('my.notes');
    Route::get('/notes/trash', [NoteController::class, 'trash'])->name('notes.trash');
    Route::get('/note/create', [NoteController::class, 'create'])->name('note.create');
    Route::post('/note/store', [NoteController::class, 'store'])->name('note.store');
    Route::get('/note/show/{slug}', [NoteController::class, 'show'])->name('note.show');
    Route::get('/note/edit/{slug}', [NoteController::class, 'edit'])->name('note.edit');
    Route::put('/note/update/{slug}', [NoteController::class, 'update'])->name('note.update');
    Route::get('/note/soft/deletes/{slug}', [NoteController::class, 'softDelete'])->name('note.softdelete');
    Route::delete('/note/destroy/{slug}', [NoteController::class, 'destroy'])->name('note.destroy');
    Route::get('/note/restore/{slug}', [NoteController::class, 'restore'])->name('note.restore');
});

require __DIR__.'/auth.php';
