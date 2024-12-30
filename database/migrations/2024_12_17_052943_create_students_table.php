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
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->tinyInteger('gender')->comment('1=Male, 2=Female');
            $table->date('birth_date')->nullable();
            $table->unsignedInteger('grade_id');
            $table->unsignedInteger('parent_id');
            $table->boolean('is_active')->default(true);
            $table->decimal('balance', 8, 2)->default(0.00);
            $table->string('profile_pic')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
