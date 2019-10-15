<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('publications', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('title',200)->nullable($value = true);
          $table->longText('abstract')->nullable($value = true);
          $table->date('written_date')->nullable($value = true);
          $table->date('published_date')->nullable($value = true);
          $table->string('keywords',500)->nullable($value = true);
          $table->unsignedInteger('publication_type_id');
          $table->foreign('publication_type_id')->references('id')->on('publication_types')->onDelete('cascade');
          $table->unsignedInteger('institution_internal_divition_id');
          $table->foreign('institution_internal_divition_id')->references('id')->on('institution_internal_divitions')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('publications');
    }
}