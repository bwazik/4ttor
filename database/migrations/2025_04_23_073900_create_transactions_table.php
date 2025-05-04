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
        Schema::create('transactions', function (Blueprint $table) {
			$table->increments('id');
            $table->tinyInteger('type')->default(1)->comment('1 => invoice, 2 => payment, 3 => refund, 4 => coupon');
            $table->integer('teacher_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned()->nullable();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->decimal('amount')->default(0.00);
            $table->decimal('balance_after')->comment('User balance after transaction');
            $table->text('description')->nullable();
            $table->tinyInteger('payment_method')->nullable()->comment('1 => cash, 2 => vodafone_cash, 3 => instapay, 4 => balance');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
