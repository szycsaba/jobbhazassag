<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_quiz_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('google_user_id')
                ->constrained('google_users')
                ->onDelete('cascade');

            $table->foreignId('quiz_id')
                ->constrained('quizzes')
                ->onDelete('cascade');

            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();

            $table->integer('score')->nullable();
            $table->integer('max_score')->nullable();

            $table->timestamps();

            $table->index(['google_user_id', 'quiz_id', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_quiz_attempts');
    }
};
