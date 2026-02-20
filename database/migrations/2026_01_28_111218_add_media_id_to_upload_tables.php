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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('media_id')->nullable()->after('avatar')->constrained('medias')->nullOnDelete();
        });

        Schema::table('carousels', function (Blueprint $table) {
            $table->foreignId('media_id')->nullable()->after('image')->constrained('medias')->nullOnDelete();
        });

        Schema::table('news', function (Blueprint $table) {
            $table->foreignId('media_id')->nullable()->after('image')->constrained('medias')->nullOnDelete();
        });

        Schema::table('about_us', function (Blueprint $table) {
            $table->foreignId('media_id')->nullable()->after('logo')->constrained('medias')->nullOnDelete();
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->foreignId('media_id')->nullable()->after('favicon')->constrained('medias')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_id');
        });

        Schema::table('carousels', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_id');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_id');
        });

        Schema::table('about_us', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_id');
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('media_id');
        });
    }
};
