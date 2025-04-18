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
        Schema::create('assignments', function (Blueprint $table) {
			$table->increments('id');
            $table->uuid('uuid')->unique();
            $table->integer('teacher_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('deadline');
            $table->integer('score')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
