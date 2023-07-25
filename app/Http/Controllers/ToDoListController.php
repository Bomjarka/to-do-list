<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use App\Models\ToDoListItem;
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

    /**
     * @param Request $request
     * @param ToDoListService $toDoListService
     * @return JsonResponse
     */
    public function createList(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $name = $request->listName;
        $userId = $request->userId;
        $deals = $request->deals;
        $createdList = $toDoListService->createToDoList($name, $userId, $deals);

        return response()->json([
            'msg' => 'OK',
            'data' => [
                'listId' => $createdList->id,
                'listName' => $createdList->name,
                'dealsCount' => $createdList->listItems()->count(),
                'created' => $createdList->created_at
            ]
        ]);
    }

    public function createDeal(Request $request, ToDoListService $toDoListService): JsonResponse
    {
        $name = $request->dealName;
        $userId = $request->userId;
        $listId = $request->listId;
        $createdDeal = $toDoListService->createDeal($name, $userId, $listId);

        return response()->json([
            'msg' => 'OK',
            'data' => [
                'dealName' => $createdDeal->name,
                'dealId' => $createdDeal->id,
            ]
        ]);
    }

    public function removeDeal(Request $request, ToDoListService $toDoListService)
    {
        $dealId = $request->dealId;
        $toDoListService->removeDeal($dealId);
        return response()->json([
            'msg' => 'OK',
            'data' => [
                'dealId' => $dealId,
            ]
        ]);
    }
    public function updateDeal(Request $request, ToDoListService $toDoListService)
    {
        $newName = $request->newName;
        $dealId = $request->dealId;
        $toDoListService->updateDeal($newName, $dealId);
        return response()->json([
            'msg' => 'OK',
            'data' => [
                'dealId' => $dealId,
                'dealName' => $newName,
            ]
        ]);
    }
}
