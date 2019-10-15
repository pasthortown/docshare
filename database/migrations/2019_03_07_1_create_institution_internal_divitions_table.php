<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionInternalDivitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('institution_internal_divitions', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('description',150)->nullable($value = true);
          $table->unsignedInteger('institution_id');
          $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('institution_internal_divitions');
    }
}