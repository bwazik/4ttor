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
        Schema::create('attendances', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('teacher_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->date('date');
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 - Present, 2 - Absent, 3 - Late, 4 - Excused');
            $table->unique(['student_id', 'date', 'teacher_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
