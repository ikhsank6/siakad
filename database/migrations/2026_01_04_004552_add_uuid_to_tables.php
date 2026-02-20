<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add UUID to users table
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        // Add UUID to roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        // Add UUID to menus table
        Schema::table('menus', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        // Add UUID to notifications table
        Schema::table('notifications', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id')->unique();
        });

        // Generate UUIDs for existing records
        $this->generateUuidsForTable('users');
        $this->generateUuidsForTable('roles');
        $this->generateUuidsForTable('menus');
        $this->generateUuidsForTable('notifications');
    }

    /**
     * Generate UUIDs for existing records in a table.
     */
    private function generateUuidsForTable(string $table): void
    {
        $records = DB::table($table)->whereNull('uuid')->get();

        foreach ($records as $record) {
            DB::table($table)
                ->where('id', $record->id)
                ->update(['uuid' => Str::uuid()->toString()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
