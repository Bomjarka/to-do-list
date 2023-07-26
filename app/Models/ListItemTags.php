<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItemTags extends Model
{
    use HasFactory;

    protected $fillable = ['list_item_id', 'tag_id'];
}