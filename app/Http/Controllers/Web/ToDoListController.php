<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Models\Tag;
use App\Models\ToDoList;
use App\Models\User;
use App\Models\UserListsPermissions;
use App\Services\PermissionService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ToDoListController extends Controller
{
    /**
     * @param User $user
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function index(User $user): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $userLists = $user->toDoLists->sortBy('created_at');

        return view('todolists', ['user' => $user, 'userLists' => $userLists]);
    }

    /**
     * @param User $user
     * @param ToDoList $toDoList
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function list(User $user, ToDoList $toDoList): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $tags = Tag::all();
        $toDoListItems = $toDoList->listItems;

        return view('todolist', [
            'user' => $user,
            'toDoList' => $toDoList,
            'toDoListItems' => $toDoListItems,
            'tags' => $tags
        ]);
    }

    /**
     * @param Request $request
     * @param ToDoList $toDoList
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function search(
        Request  $request,
        User     $user,
        ToDoList $toDoList
    ): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $toDoListItems = $toDoList->listItems;
        $searchItem = $request->get('search-items');
        $searchTagsIds = $request->get('search-tags');

        $foundToDoListItems = $toDoListItems->filter(function ($item) use ($searchItem, $searchTagsIds) {
            if (\str_contains($item->name, $searchItem) === false) {
                return false;
            }
            if ($searchTagsIds !== null) {
                $hasTag = false;
                foreach ($searchTagsIds as $searchTagsId) {
                    if (!$item->getTags()->contains('id', $searchTagsId)) {
                        continue;
                    }
                    $hasTag = true;
                    break;
                }

                return $hasTag;
            }

            return true;
        })->values();

        return view('todolist', [
            'user' => $user,
            'toDoList' => $toDoList,
            'toDoListItems' => $foundToDoListItems,
            'tags' => Tag::all(),
        ]);
    }

    public function shareListToUsers(Request $request, User $user, PermissionService $permissionService)
    {
        $usersIds = $request->get('selected-users');
        $listId = $request->get('list-id');
        $sharedList = ToDoList::find($listId);
        if (!$sharedList) {
            return redirect()->back()->withErrors(['msg' => 'No list found']);
        }

        $readPermission = ((bool)$request->get('read-list') === true) ? Permissions::READ_LIST : null;
        $writePermission = ((bool)$request->get('write-list') === true) ? Permissions::WRITE_LIST : null;

        foreach ($usersIds as $usersId) {
            $userToShare = User::find($usersId);
            if ($userToShare) {
                $permissionService->addPermissions($userToShare, [$readPermission, $writePermission]);
                $permissionService->addUserListsPermission($userToShare, $sharedList, [$readPermission, $writePermission]);
            }
        }

        return redirect()->back();
    }
}
