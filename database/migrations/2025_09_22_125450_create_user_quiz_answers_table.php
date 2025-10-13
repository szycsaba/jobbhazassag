<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_quiz_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_id')
                ->constrained('user_quiz_attempts')
                ->onDelete('cascade');

            $table->foreignId('question_id')
                ->constrained('quiz_questions')
                ->onDelete('cascade');

            $table->foreignId('selected_option_id')
                ->constrained('quiz_options')
                ->onDelete('cascade');

            $table->boolean('is_correct');
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_quiz_answers');
    }
};
