<?php

namespace App\Services;


use App\Models\Permissions;
use App\Models\ToDoList;
use App\Models\User;
use App\Models\UserListsPermissions;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    /**
     * @param User $user
     * @param Permission $permission
     * @return void
     */
    private function addPermission(User $user, Permission $permission): void
    {
        $user->givePermissionTo($permission->name);
    }

    /**
     * @param User $user
     * @param array $permissions
     * @return void
     */
    public function addPermissions(User $user, array $permissions): void
    {
        foreach ($permissions as $permissionName) {
            $permission = Permission::whereName($permissionName)->first();
            if ($permission) {
                $this->addPermission($user, $permission);
            }
        }
    }

    /**
     * @param UserListsPermissions $userListsPermissions
     * @param string|null $permission
     * @return void
     */
    private function enablePermissionAtUserListPermission(
        UserListsPermissions $userListsPermissions,
        ?string              $permission
    ): void
    {
        switch ($permission) {
            case null:
                break;
            case Permissions::READ_LIST :
                $userListsPermissions->read = true;
                $userListsPermissions->save();
                break;
            case Permissions::WRITE_LIST :
                $userListsPermissions->write = true;
                $userListsPermissions->save();
                break;
        }
    }

    /**
     * @param User $user
     * @param ToDoList $toDoList
     * @param array $permissions
     * @return void
     */
    public function addUserListsPermission(User $user, ToDoList $toDoList, array $permissions): void
    {
        $listPermission = $user->listPermission($toDoList->id);

        if (!$listPermission) {
            $listPermission = UserListsPermissions::create([
                'user_id' => $user->id,
                'list_id' => $toDoList->id,
            ]);
        }

        foreach ($permissions as $permission) {
            $this->enablePermissionAtUserListPermission($listPermission, $permission);
        }
    }

    /**
     * @param User $user
     * @param ToDoList $toDoList
     * @param array $permissions
     * @return void
     */
    public function shareListToUser(User $user, ToDoList $toDoList, array $permissions): void
    {
        $this->addPermissions($user, $permissions);
        $this->addUserListsPermission($user, $toDoList, $permissions);
    }
}
