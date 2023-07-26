<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

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

    public function tags()
    {
//        $tags = Arr::flatten(json_decode($this->tags));
//
//        return implode(',', $tags);
    }
}
