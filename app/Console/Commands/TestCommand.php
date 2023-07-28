<?php

namespace App\Console\Commands;

use App\Models\ToDoList;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

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
        $list = ToDoList::find(63);
        dd($list->sharedTo($list));
    }
}
