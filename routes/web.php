<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TasksController;
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

Route::redirect('/', '/tasks');

Route::get('/tasks', [TasksController::class, 'index'])->middleware(['auth', 'verified'])->name('tasks.index');
Route::post('/tasks', [TasksController::class, 'store'])->middleware(['auth', 'verified'])->name('tasks.store');
Route::get('/tasks/{date}', [TasksController::class, 'show'])->middleware(['auth', 'verified'])->name('tasks.show');
Route::put('/tasks/{date}', [TasksController::class, 'update'])->middleware(['auth', 'verified'])->name('tasks.update');
Route::get('/tasks/{date}/edit', [TasksController::class, 'edit'])->middleware(['auth', 'verified'])->name('tasks.edit');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
