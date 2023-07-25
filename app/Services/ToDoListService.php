<?php

namespace App\Services;

use App\Models\ToDoList;
use App\Models\ToDoListItem;

class ToDoListService
{
    /**
     * @param string $name
     * @param int $userId
     * @param array $deals
     * @return mixed
     */
    public function createToDoList(string $name, int $userId, array $deals): mixed
    {
        $toDoList = ToDoList::create(['name' => $name, 'user_id' => $userId]);
        if (!empty($deals)) {
            foreach ($deals as $deal) {
                ToDoListItem::create(['name' => $deal, 'user_id' => $userId, 'list_id' => $toDoList->id]);
            }
        }

        return $toDoList;

    }

    /**
     * @param string $name
     * @param int $userId
     * @param int $listId
     * @return mixed
     */
    public function createDeal(string $name, int $userId, int $listId): mixed
    {
      return ToDoListItem::create(['name' => $name, 'user_id' => $userId, 'list_id' => $listId]);
    }

    /**
     * @param int $dealId
     * @return void
     */
    public function removeDeal(int $dealId): void
    {
        $toDoListItem = ToDoListItem::find($dealId);
        if ($toDoListItem) {
            $toDoListItem->delete();
        }
    }

    /**
     * @param string $newName
     * @param int $dealId
     * @return void
     */
    public function updateDeal(string $newName, int $dealId): void
    {
        $toDoListItem = ToDoListItem::find($dealId);
        if ($toDoListItem) {
            $toDoListItem->name = $newName;
            $toDoListItem->save();
        }
    }
}
