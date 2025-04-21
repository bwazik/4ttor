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
        Schema::create('teacher_resources', function (Blueprint $table) {
			$table->increments('id');
            $table->uuid('uuid')->unique();
            $table->integer('teacher_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->default(0);
            $table->string('video_url')->nullable();
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_resources');
    }
};
