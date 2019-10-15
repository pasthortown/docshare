<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('institution_logos', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('institution_logo_file_type',50)->nullable($value = true);
          $table->string('institution_logo_file_name',50)->nullable($value = true);
          $table->longText('institution_logo_file')->nullable($value = true);
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
       Schema::dropIfExists('institution_logos');
    }
}