<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration {

    private const PERMISSION_NAME = 'read_lists';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Permission::whereName(self::PERMISSION_NAME)->first()) {
            Permission::create(['name' => 'read_lists']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereName(self::PERMISSION_NAME)->first()->delete();
    }
};
