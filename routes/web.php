<?php

use App\Http\Controllers\Web\TagController;
use App\Http\Controllers\Web\ToDoListController;
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
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(static function () {
    Route::get('/todo-lists', [ToDoListController::class, 'index'])->name('todo-lists-index');
    Route::get('/todo-lists/{todolist}', [ToDoListController::class, 'list'])->name('todo-list');
    Route::get('/tags', [TagController::class, 'index'])->name('tags-index');
});
