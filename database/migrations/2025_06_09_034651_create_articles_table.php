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
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->integer('category_id')->unsigned();
            $table->tinyInteger('audience')->default(0)->index()->comment('1 => teachers, 2 => students, 3 => assistants, 4 => parents, 5 => teachers & assistants, 6 => students & parents, 7 => all');;
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pinned')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
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
        Schema::dropIfExists('articles');

        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign('articles_category_id_foreign');
        });
    }
};
