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
            $table->date('birthday')->nullable();
            $table->string('telegram', 64)->nullable();
            $table->string('vk', 64)->nullable();
            $table->string('steam', 64)->nullable();
            $table->string('website')->nullable();
            $table->string('timezone', 64)->default('Asia/Irkutsk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birthday', 'telegram', 'vk', 'steam', 'website', 'timezone']);
        });
    }
};
