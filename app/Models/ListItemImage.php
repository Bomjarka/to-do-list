<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $preview_name
 * @property int $list_item_id
 */
class ListItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',  'preview_name', 'list_item_id'
    ];

    public function listItem(): BelongsTo
    {
        return $this->belongsTo(ToDoListItem::class);
    }
}
