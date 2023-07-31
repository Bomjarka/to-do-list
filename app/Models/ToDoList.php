<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int $user_id
 * @property string $name
 */
class ToDoList extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listItems()
    {
        return $this->hasMany(ToDoListItem::class, 'list_id');
    }

    public function listPermissions()
    {
        return $this->hasMany(UserListsPermissions::class, 'list_id');
    }

    public function sharedTo(ToDoList $toDoList)
    {
        $userListPermissions = $this->listPermissions;
        $users = [];
        foreach ($userListPermissions as $userListPermission) {
            $users[] =$userListPermission->user;
        }

        return $users;
    }
}
