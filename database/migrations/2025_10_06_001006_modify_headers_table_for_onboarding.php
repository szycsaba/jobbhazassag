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
        Schema::table('headers', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['article_id']);
            
            // Make article_id nullable and allow 0 for onboarding
            $table->unsignedBigInteger('article_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headers', function (Blueprint $table) {
            // Restore the foreign key constraint
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade')->change();
        });
    }
};