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
        Schema::create('zooms', function (Blueprint $table) {
			$table->increments('id');
            $table->uuid('uuid')->unique();
            $table->integer('teacher_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->string('meeting_id')->nullable();
            $table->string('topic');
            $table->integer('duration');
            $table->string('password')->nullable();
            $table->dateTime('start_time');
            $table->text('start_url')->nullable();
            $table->text('join_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zooms');
    }
};
