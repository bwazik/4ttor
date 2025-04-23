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
        Schema::create('invoices', function (Blueprint $table) {
			$table->increments('id');
            $table->uuid('uuid')->unique();
            $table->tinyInteger('type')->default(1)->comment('1 => subscription, 2 => fee');
            $table->integer('teacher_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned()->nullable();
            $table->integer('fee_id')->unsigned()->nullable();
            $table->integer('subscription_id')->unsigned()->nullable();
            $table->decimal('amount')->default(0.00);
            $table->date('date');
            $table->date('due_date');
            $table->tinyInteger('status')->default(1)->comment('1 => pending, 2 => paid, 3 => overdue, 4 => canceled');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
