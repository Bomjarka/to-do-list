<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tag_id
 * @property int $list_item_id
 */
class ListItemTags extends Model
{
    use HasFactory;

    protected $fillable = ['list_item_id', 'tag_id'];
}
