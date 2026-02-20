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
        // Add deleted_at to users table
        if (! Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to roles table
        if (! Schema::hasColumn('roles', 'deleted_at')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to menus table
        if (! Schema::hasColumn('menus', 'deleted_at')) {
            Schema::table('menus', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to notifications table
        if (! Schema::hasColumn('notifications', 'deleted_at')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to carousels table
        if (! Schema::hasColumn('carousels', 'deleted_at')) {
            Schema::table('carousels', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to news_categories table
        if (! Schema::hasColumn('news_categories', 'deleted_at')) {
            Schema::table('news_categories', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to news table
        if (! Schema::hasColumn('news', 'deleted_at')) {
            Schema::table('news', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to about_us table
        if (! Schema::hasColumn('about_us', 'deleted_at')) {
            Schema::table('about_us', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('carousels', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('news_categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('about_us', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
