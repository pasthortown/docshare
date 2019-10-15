<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('publication_attachments', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->string('publication_attachment_file_type',50)->nullable($value = true);
          $table->string('publication_attachment_file_name',50)->nullable($value = true);
          $table->longText('publication_attachment_file')->nullable($value = true);
          $table->unsignedInteger('publication_id');
          $table->foreign('publication_id')->references('id')->on('publications')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('publication_attachments');
    }
}