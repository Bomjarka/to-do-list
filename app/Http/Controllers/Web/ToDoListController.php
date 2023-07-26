<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ToDoList;
use App\Services\ToDoListService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ToDoListController extends Controller
{
    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $user = auth()->user();
        $userLists = $user->toDoLists->sortBy('created_at');

        return view('todolists', ['userLists' => $userLists]);
    }

    public function list(ToDoList $todolist)
    {
        return view('todolist', ['toDoList' => $todolist]);
    }
}
