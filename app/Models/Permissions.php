<?php

namespace App\Models;

use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\Models\Permission;

class Permissions extends Permission
{
    public const READ_LIST = 'read_lists';
    public const WRITE_LIST = 'write_lists';
}
