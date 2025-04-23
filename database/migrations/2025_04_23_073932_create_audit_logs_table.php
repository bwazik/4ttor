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
        Schema::create('audit_logs', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('teacher_id')->unsigned()->nullable();
            $table->integer('assistant_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned()->nullable();
            $table->string('action');
            $table->text('details')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
