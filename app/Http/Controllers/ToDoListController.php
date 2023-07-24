<?php

namespace App\Http\Controllers;

use App\Services\ToDoListService;
use Illuminate\Http\Request;

class ToDoListController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userLists = $user->toDoLists;

        return view('todolist', ['userLists' => $userLists]);
    }
    public function createList(Request $request, ToDoListService $toDoListService)
    {
        $name = $request->listName;
        $userId = $request->userId;
        $toDoListService->createToDoList($name, $userId);

        return response('OK', 200);
    }
}
