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
        Schema::create('article_type_attributes', function (Blueprint $table) {
            $table->foreignId('article_type_id')->primary()->constrained('article_types');
            $table->string('background');
            $table->string('text');
            $table->string('default_background');
            $table->string('default_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_type_attributes');
    }
};
