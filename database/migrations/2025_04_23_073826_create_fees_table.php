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
        Schema::create('fees', function (Blueprint $table) {
			$table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->decimal('amount')->default(0.00);
            $table->integer('teacher_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->tinyInteger('frequency')->default(1)->comment('1 => one-time, 2 => monthly, 3 => custom');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
