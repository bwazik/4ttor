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
        Schema::create('student_quiz_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('quiz_id')->unsigned();
            $table->integer('question_id')->unsigned();
            $table->integer('display_order')->comment('Order shown to student (1, 2, 3, ...)');
            $table->json('answer_order')->nullable()->comment('Array of answer_id order for this question');
            $table->timestamps();
            $table->unique(['student_id', 'quiz_id', 'question_id']);
            $table->index(['student_id', 'quiz_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_quiz_order');
    }
};
