<?php

namespace Database\Seeders;

use App\Models\Permissions;
use App\Models\ToDoList;
use App\Models\User;
use App\Models\UserListsPermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserListsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = User::all()->random(1)->first();
            $list = ToDoList::where('user_id', '!=', $user->id)->get()->random(1)->first();

            $permissionList = null;
            if ($list) {
                $permissionList = UserListsPermissions::whereUserId($user->id)->whereListId($list->id)->first();
            }

            if (!$permissionList) {
                $read = (bool)random_int(0, 1);
                $write = (bool)random_int(0, 1);
                if (!$read) {
                    $write = false;
                }

                $readPermission = ($read === true) ? Permissions::READ_LIST : null;
                $writePermission = ($write === true) ? Permissions::WRITE_LIST : null;

                $user->givePermissionTo($readPermission);
                $user->givePermissionTo($writePermission);

                UserListsPermissions::create(['user_id' => $user->id, 'list_id' => $list->id, 'read' => $read, 'write' => $write]);
            }
        }
    }
}
