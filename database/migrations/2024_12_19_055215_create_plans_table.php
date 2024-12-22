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
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 8, 2);
            $table->decimal('term_price', 8, 2)->nullable();
            $table->decimal('year_price', 8, 2)->nullable();

            // Limits
            $table->integer('student_limit');
            $table->integer('parent_limit');
            $table->integer('assistant_limit');
            $table->integer('group_limit');
            $table->integer('quiz_monthly_limit');
            $table->integer('quiz_term_limit');
            $table->integer('quiz_year_limit');
            // $table->integer('exam_monthly_limit');
            // $table->integer('exam_term_limit');
            // $table->integer('exam_year_limit');
            $table->integer('assignment_monthly_limit');
            $table->integer('assignment_term_limit');
            $table->integer('assignment_year_limit');

            // Reports
            $table->boolean('attendance_reports')->default(false);
            $table->boolean('financial_reports')->default(false);
            $table->boolean('performance_reports')->default(false);

            // Additional Features
            $table->boolean('whatsapp_messages')->default(false);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
