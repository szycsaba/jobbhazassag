<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_reflection_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('google_user_id')
                ->constrained('google_users')
                ->onDelete('cascade');
            $table->foreignId('reflection_question_id')
                ->constrained('reflection_questions')
                ->onDelete('cascade');
            $table->text('note_text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_reflection_notes');
    }
};
