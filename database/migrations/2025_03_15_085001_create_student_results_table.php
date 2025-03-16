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
        Schema::create('student_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('quiz_id')->unsigned();
            $table->decimal('total_score', 5, 2);
            $table->integer('attempt_number')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();

            $table->index(['student_id', 'quiz_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_results');
    }
};
