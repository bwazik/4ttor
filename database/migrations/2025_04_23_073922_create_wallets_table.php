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
        Schema::create('wallets', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('teacher_id')->unsigned()->nullable();
            $table->decimal('balance')->default(0.00)->comment('Earnings from fees or subscriptions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
