<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebboardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('webboards', function (Blueprint $table) {
      // $table->engine = 'MyISAM';
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('title');
      $table->string('slug')->nullable();
      $table->string('author')->nullable()->comment('ผู้เขียน');
      $table->integer('view')->nullable();
      $table->ipAddress('ip')->nullable();
      $table->longText('detail')->nullable()->comment('รายละเอียด');
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
    Schema::dropIfExists('webboards');
  }
}
