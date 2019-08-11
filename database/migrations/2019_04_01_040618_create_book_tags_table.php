<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedBigInteger('book_id');
        });
        Schema::table('book_tags', function(Blueprint $table){
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_tags', function(Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['book_id']);
        });
        Schema::dropIfExists('book_tags');
    }
}
