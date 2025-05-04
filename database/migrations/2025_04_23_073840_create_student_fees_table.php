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
        Schema::create('student_fees', function (Blueprint $table) {
			$table->increments('id');
            $table->uuid('uuid')->unique();
            $table->integer('student_id')->unsigned();
            $table->integer('fee_id')->unsigned();
            $table->decimal('discount', 5, 2)->default(0.00)->comment('Percentage discount, e.g., 10.00 for 10%');
            $table->boolean('is_exempted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_fees');
    }
};
