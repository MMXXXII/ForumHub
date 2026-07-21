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
        Schema::table('wall_posts', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('author_id')->constrained('wall_posts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wall_posts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};
