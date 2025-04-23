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
        Schema::create('teachers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->integer('subject_id')->unsigned();
            $table->integer('plan_id')->unsigned()->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->decimal('balance')->default(0.00);
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
        Schema::dropIfExists('teachers');
    }
};
