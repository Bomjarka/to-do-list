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
    Route::prefix('/user/{user}')->group(static function () {
        Route::get('/todo-lists', [ToDoListController::class, 'index'])->name('todo-lists-index');
        Route::get('/todo-lists/{toDoList}', [ToDoListController::class, 'list'])->name('todo-list');
        Route::post('/todo-lists/{toDoList}/search', [ToDoListController::class, 'search'])->name('search');
        Route::post('/todo-lists/share-list', [ToDoListController::class, 'shareListToUsers'])->name('share-list');
    });
    Route::get('/tags', [TagController::class, 'index'])->name('tags-index');
    Route::get('/users-lists', [ToDoListController::class, 'indexUsersLists'])->name('users-lists');
});
