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
        Schema::create('onsite_blocks', function (Blueprint $table) {
            $table->id();
            $table->integer('position');
            $table->foreignId('type_id')->constrained('article_types')->onDelete('restrict');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onsite_blocks');
        Schema::dropIfExists('article_types');
    }
};
