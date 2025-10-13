<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reflection_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reflection_id')->constrained('reflections')->onDelete('cascade');
            $table->integer('position');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reflection_questions');
    }
};
