<?php

namespace App\Services;

use App\Models\ToDoList;
use App\Models\ToDoListItem;
use App\Models\User;

class ToDoListService
{
    /**
     * @param string $name
     * @param User $user
     * @param array $deals
     * @return mixed
     */
    public function createToDoList(string $name, User $user, array $deals): mixed
    {
        $toDoList = ToDoList::create(['name' => $name, 'user_id' => $user->id]);
        if (!empty($deals)) {
            foreach ($deals as $deal) {
                ToDoListItem::create(['name' => $deal, 'user_id' => $user->id, 'list_id' => $toDoList->id]);
            }
        }

        return $toDoList;

    }

    /**
     * @param string $name
     * @param User $user
     * @param ToDoList $toDoList
     * @return mixed
     */
    public function createToDoListItem(string $name, User $user, ToDoList $toDoList): mixed
    {
        return ToDoListItem::create(['name' => $name, 'user_id' => $user->id, 'list_id' => $toDoList->id]);
    }

    /**
     * @param ToDoListItem $toDoListItem
     * @return void
     */
    public function removeToDoListItem(ToDoListItem $toDoListItem): void
    {
        $toDoListItem->delete();
    }

    /**
     * @param string $newName
     * @param ToDoListItem $toDoListItem
     * @return void
     */
    public function updateToDoListItem(string $newName, ToDoListItem $toDoListItem): void
    {
        $toDoListItem->name = $newName;
        $toDoListItem->save();
    }
}
