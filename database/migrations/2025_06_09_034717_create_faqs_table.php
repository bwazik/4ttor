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
        Schema::create('faqs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned();
            $table->tinyInteger('audience')->default(0)->index()->comment('1 => teachers, 2 => students, 3 => assistants, 4 => parents, 5 => teachers & assistants, 6 => students & parents, 7 => all');;
            $table->text('question');
            $table->text('answer');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_at_landing')->default(false)->index();
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign('faqs_category_id_foreign');
        });
    }
};
