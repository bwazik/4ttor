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
        Schema::create('article_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->tinyInteger('type')->default(1)->comment('1 => text, 2 => image');
            $table->text('content');
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::table('article_contents', function (Blueprint $table) {
            $table->foreign('article_id')->references('id')->on('articles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_contents');

        Schema::table('article_contents', function (Blueprint $table) {
            $table->dropForeign('article_contents_article_id_foreign');
        });
    }
};
