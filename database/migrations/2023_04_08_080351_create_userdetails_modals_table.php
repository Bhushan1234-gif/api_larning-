<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserdetailsModalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userdetails', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('gender',[0,1])->comment('0=> male , 1 => female ');
            $table->string('hobbis'); 
            $table->text('address');
            $table->text('image')->nullable();
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
        Schema::dropIfExists('userdetails');
    }
}
