<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\ToDoListItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListItemTags>
 */
class ListItemTagsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'list_item_id' => ToDoListItem::all()->random(1)->first()->id,
            'tag_id' => Tag::all()->random(1)->first()->id,
        ];
    }
}
