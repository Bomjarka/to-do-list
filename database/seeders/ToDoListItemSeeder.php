<?php

namespace Database\Seeders;

use App\Models\ToDoListItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToDoListItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ToDoListItem::factory()->count(10)->create();
    }
}
