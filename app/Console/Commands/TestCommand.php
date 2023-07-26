<?php

namespace App\Console\Commands;

use App\Models\Tag;
use App\Models\ToDoListItem;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $item = ToDoListItem::find(15);
        $tagNames = $item->getTags()->pluck('name');
        dd($tagNames->implode(','));

    }
}
