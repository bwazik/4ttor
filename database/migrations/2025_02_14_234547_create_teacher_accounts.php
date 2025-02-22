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
        Schema::create('teacher_accounts', function (Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('type')->default(1)->comment('1 - Invoice, 2 - Receipt, 3 - Refund');
            $table->integer('teacher_id')->unsigned();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->integer('receipt_id')->unsigned()->nullable();
            $table->integer('refund_id')->unsigned()->nullable();
            $table->decimal('debit')->default(0.00)->nullable();
            $table->decimal('credit')->default(0.00)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_accounts');
    }
};
