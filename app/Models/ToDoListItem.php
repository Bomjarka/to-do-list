<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ToDoListItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'list_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(ToDoList::class);
    }

    public function image(): HasOne
    {
        return $this->hasOne(ListItemImage::class, 'list_item_id');
    }

    /**
     * @return mixed
     */
    public function getTags(): Collection
    {
        return Tag::whereIn('id', ListItemTags::whereListItemId($this->id)->get()->pluck('tag_id'))->get();
    }
}
