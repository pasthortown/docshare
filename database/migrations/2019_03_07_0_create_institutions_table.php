<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('institutions', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('name',255)->nullable($value = true);
          $table->string('address',255)->nullable($value = true);
          $table->float('address_map_latitude',24,16)->nullable($value = true);
          $table->float('address_map_longitude',24,16)->nullable($value = true);
          $table->string('phone_number',20)->nullable($value = true);
          $table->string('web',255)->nullable($value = true);
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('institutions');
    }
}