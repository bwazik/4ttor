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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->integer('teacher_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->string('name');
            $table->integer('duration');
            $table->tinyInteger('quiz_mode')->default(1)->comment('1 => fixed, 2 => flexible');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('randomize_answers')->default(false);
            $table->boolean('show_result')->default(false);
            $table->boolean('allow_review')->default(false);
            $table->timestamps();

            $table->index(['start_time', 'end_time', 'quiz_mode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
