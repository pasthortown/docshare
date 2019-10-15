<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('publication_comments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->longText('content')->nullable($value = true);
          $table->unsignedInteger('publication_id');
          $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
          $table->unsignedInteger('person_id');
          $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('publication_comments');
    }
}