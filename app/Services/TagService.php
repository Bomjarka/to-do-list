<?php

namespace App\Services;

use App\Models\ListItemTags;
use App\Models\Tag;
use App\Models\ToDoListItem;

class TagService
{
    /**
     * @param $tagName
     * @return mixed
     */
    public function createTag($tagName): mixed
    {
        return Tag::create(['name' => $tagName]);
    }

    /**
     * @param Tag $tag
     * @param string $newName
     * @return void
     */
    public function updateTag(Tag $tag, string $newName): void
    {
        $tag->name = $newName;
        $tag->save();
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function removeTag(Tag $tag): void
    {
        $tag->delete();
    }

    /**
     * @param Tag $tag
     * @param ToDoListItem $listItem
     * @return mixed
     */
    public function addTagToListItem(Tag $tag, ToDoListItem $listItem): mixed
    {
        return ListItemTags::create(['list_item_id' => $listItem->id, 'tag_id' => $tag->id]);
    }

    /**
     * @param Tag $tag
     * @param ToDoListItem $listItem
     * @return void
     */
    public function removeTagFromListItem(Tag $tag, ToDoListItem $listItem): void
    {
        ListItemTags::whereListItemId($listItem->id)
            ->whereTagId($tag->id)
            ->first()->delete();
    }
}
