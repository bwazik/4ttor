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
        Schema::create('zoom_accounts', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('teacher_id')->unsigned()->unique();
            $table->text('account_id');
            $table->text('client_id');
            $table->text('client_secret');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zoom_accounts');
    }
};
