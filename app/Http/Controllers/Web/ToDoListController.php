<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\ToDoList;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

    /**
     * @param ToDoList $toDoList
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function list(ToDoList $toDoList): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $tags = Tag::all();
        $toDoListItems = $toDoList->listItems;

        return view('todolist', ['toDoList' => $toDoList, 'toDoListItems' => $toDoListItems, 'tags' => $tags]);
    }

    /**
     * @param Request $request
     * @param ToDoList $toDoList
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function search(
        Request $request,
        ToDoList $toDoList
    ): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $toDoListItems = $toDoList->listItems;
        $searchItem = $request->get('search-items');
        $searchTagsIds = $request->get('search-tags');

        $foundToDoListItems = $toDoListItems->filter(function ($item) use ($searchItem) {
            return str_contains($item->name, $searchItem);
        })->values();

        if ($searchTagsIds) {
            $foundToDoListItems = $foundToDoListItems->filter(function ($item) use ($searchTagsIds) {
                $items = [];
                foreach ($searchTagsIds as $searchTagsId) {
                    if (!$item->getTags()->contains('id', $searchTagsId)) {
                        continue;
                    }
                    $items[] = $item;
                }

                return $items;
            });
        }

        return view('todolist', [
            'toDoList' => $toDoList,
            'toDoListItems' => $foundToDoListItems,
            'tags' => Tag::all(),
        ]);
    }
}
