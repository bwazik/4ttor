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
            $table->date('date');
            $table->decimal('amount', 8, 2)->default(0.00);
            $table->unsignedInteger('fee_id')->nullable();
            $table->unsignedInteger('plan_id')->nullable();
            $table->unsignedInteger('teacher_id')->nullable();
            $table->unsignedInteger('student_id')->nullable();
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
