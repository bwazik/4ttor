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
        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('stage_id')->references('id')->on('stages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreign('subject_id')->references('id')->on('subjects')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('teacher_grade', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->foreign('grade_id')->references('id')->on('grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('parent_id')->references('id')->on('parents')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('student_teacher', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('assistants', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign('grades_stage_id_foreign');
        });
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign('teachers_subject_id_foreign');
            $table->dropForeign('teachers_plan_id_foreign');
        });
        Schema::table('teacher_grade', function (Blueprint $table) {
            $table->dropForeign('teacher_grade_teacher_id_foreign');
            $table->dropForeign('teacher_grade_grade_id_foreign');
        });
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign('students_grade_id_foreign');
            $table->dropForeign('students_parent_id_foreign');
        });
        Schema::table('student_teacher', function (Blueprint $table) {
            $table->dropForeign('student_teacher_student_id_foreign');
            $table->dropForeign('student_teacher_teacher_id_foreign');
        });
        Schema::table('assistants', function (Blueprint $table) {
            $table->dropForeign('assistants_teacher_id_foreign');
        });
    }
};
