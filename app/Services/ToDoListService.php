<?php

namespace App\Services;

use App\Models\ToDoList;

class ToDoListService
{
    public function createToDoList(string $name, int $userId)
    {
        ToDoList::create(['name' => $name, 'user_id' => $userId]);
    }
}
