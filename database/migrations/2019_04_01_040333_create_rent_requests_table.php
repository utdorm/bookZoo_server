<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rent_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('book_id')->nullable();
            $table->string('renter_name');
            $table->string('phone_number');
            $table->string('book_name');
            $table->double('deposit')->default(0);
            $table->date('return_date');
            $table->enum('status', ['pending', 'renting', 'returned']);
            $table->date('confirmed_at')->nullable();
            $table->date('returned_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('rent_requests', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on("users")->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rent_requests', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropForeign(['book_id']);
        });
        Schema::dropIfExists('rent_requests');
    }
}
