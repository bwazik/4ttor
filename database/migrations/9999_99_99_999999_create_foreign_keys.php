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
        # Start Platform Managment Tables
        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('stage_id')->references('id')->on('stages')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        # End Platform Managment Tables

        # Start Users Management Tables
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
        Schema::table('groups', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('student_group', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        # End Users Management Tables

        # Start Finance Tables
        Schema::table('fees', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('grade_id')->references('id')->on('grades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('fee_id')->references('id')->on('fees')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('student_accounts', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('receipt_id')->references('id')->on('receipts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('refund_id')->references('id')->on('refunds')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('teacher_accounts', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('receipt_id')->references('id')->on('receipts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('refund_id')->references('id')->on('refunds')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('receipts', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('refunds', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('student_id')->references('id')->on('students')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        # End Finance Tables

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        # Start Platform Managment Tables
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign('grades_stage_id_foreign');
        });
        # End Platform Managment Tables

        # Start Users Management Tables
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
        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_teacher_id_foreign');
        });
        Schema::table('student_group', function (Blueprint $table) {
            $table->dropForeign('student_group_student_id_foreign');
            $table->dropForeign('student_group_group_id_foreign');
        });
        # End Users Management Tables

        # Start Finance Tables
        Schema::table('fees', function (Blueprint $table) {
            $table->dropForeign('fees_teacher_id_foreign');
            $table->dropForeign('fees_grade_id_foreign');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_fee_id_foreign');
            $table->dropForeign('invoices_plan_id_foreign');
            $table->dropForeign('invoices_teacher_id_foreign');
            $table->dropForeign('invoices_student_id_foreign');
        });
        Schema::table('student_accounts', function (Blueprint $table) {
            $table->dropForeign('student_accounts_student_id_foreign');
            $table->dropForeign('student_accounts_invoice_id_foreign');
            $table->dropForeign('student_accounts_receipt_id_foreign');
            $table->dropForeign('student_accounts_refund_id_foreign');
        });
        Schema::table('teacher_accounts', function (Blueprint $table) {
            $table->dropForeign('teacher_accounts_teacher_id_foreign');
            $table->dropForeign('teacher_accounts_invoice_id_foreign');
            $table->dropForeign('teacher_accounts_receipt_id_foreign');
            $table->dropForeign('teacher_accounts_refund_id_foreign');
        });
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign('receipts_teacher_id_foreign');
            $table->dropForeign('receipts_student_id_foreign');
        });
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropForeign('refunds_teacher_id_foreign');
            $table->dropForeign('refunds_student_id_foreign');
        });
        # End Finance Tables

    }
};
