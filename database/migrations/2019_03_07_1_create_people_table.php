<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('people', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('identification',20)->nullable($value = true);
          $table->string('name',255)->nullable($value = true);
          $table->string('last_name',255)->nullable($value = true);
          $table->string('mobile_number',20)->nullable($value = true);
          $table->string('home_number',20)->nullable($value = true);
          $table->date('birthday')->nullable($value = true);
          $table->string('email',255)->nullable($value = true);
          $table->unsignedInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('people');
    }
}