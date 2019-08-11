<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 150);
            $table->string('author', 150);
            $table->longText('summary');
            $table->enum('status', ['available', 'renting'])->default('available');
            $table->date('return_at')->nullable();
            $table->enum('isTrending', ['yes', 'no'])->default('no');
            $table->enum('isNewArrival', ['yes', 'no'])->nullable();
            $table->double('price');
            $table->double('rentingPrice');
            $table->enum('condition', ['Brand New', 'Good', 'Medium', 'Low']);
            $table->string('image', 200)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
