<?php

use App\Models\Permissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Permission::whereName(Permissions::WRITE_LIST)->first()) {
            Permission::create(['name' => Permissions::WRITE_LIST]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereName(Permissions::WRITE_LIST)->first()->delete();
    }
};
