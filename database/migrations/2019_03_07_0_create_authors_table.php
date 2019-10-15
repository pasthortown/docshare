<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('authors', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',50)->nullable($value = true);
          $table->string('last_name',50)->nullable($value = true);
          $table->string('affiliation',255)->nullable($value = true);
          $table->string('email',255)->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('authors');
    }
}