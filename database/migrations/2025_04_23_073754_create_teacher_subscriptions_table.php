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
        Schema::create('teacher_subscriptions', function (Blueprint $table) {
			$table->increments('id');
            $table->integer('teacher_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('amount')->default(0.00);
            $table->tinyInteger('status')->default(1)->comment('1 => active, 2 => canceled, 3 => expired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subscriptions');
    }
};
