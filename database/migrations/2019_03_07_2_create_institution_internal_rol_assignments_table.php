<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionInternalRolAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('institution_internal_rol_assignments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->dateTime('date')->nullable($value = true);
          $table->unsignedInteger('institution_internal_rol_id');
          $table->foreign('institution_internal_rol_id')->references('id')->on('institution_internal_rols')->onDelete('cascade');
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
       Schema::dropIfExists('institution_internal_rol_assignments');
    }
}