<?php

namespace Database\Seeders;

use App\Models\ListItemTags;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ListItemTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ListItemTags::factory()->count(10)->create();
    }
}
