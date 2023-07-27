<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property int $list_id
 * @property bool $read
 * @property bool $write
 */
class UserListsPermissions extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'list_id', 'read', 'write'];
}
