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
        Schema::create('student_accounts', function (Blueprint $table) {
			$table->increments('id');
            $table->boolean('type')->default(1)->comment('1 - Invoice, 2 - Receipt, 3 - Refund');
            $table->integer('student_id')->unsigned();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->integer('receipt_id')->unsigned()->nullable();
            $table->integer('refund_id')->unsigned()->nullable();
            $table->decimal('debit')->nullable();
            $table->decimal('credit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_accounts');
    }
};
