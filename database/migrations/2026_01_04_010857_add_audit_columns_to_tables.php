<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['users', 'roles', 'menus', 'notifications'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('created_by')->nullable()->after('created_at');
                $table->string('updated_by')->nullable()->after('updated_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'roles', 'menus', 'notifications'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['created_by', 'updated_by']);
            });
        }
    }
};
