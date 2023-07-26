<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\User;

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


}
