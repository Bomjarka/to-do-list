<?php

use App\Http\Controllers\API\ToDoListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/create-list', [ToDoListController::class, 'createList'])->name('create-list');
Route::post('/create-deal', [ToDoListController::class, 'createToDoListItem'])->name('create-deal');
Route::post('/remove-deal', [ToDoListController::class, 'removeToDoListItem'])->name('remove-deal');
Route::post('/update-deal', [ToDoListController::class, 'updateToDoListItem'])->name('update-deal');
Route::post('/add-image', [ToDoListController::class, 'addImage'])->name('add-image');
