<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTypesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('news_types', function (Blueprint $table) {
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('title')->nullable();
      $table->string('slug')->nullable();
      $table->integer('sort')->default(0);
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
    Schema::dropIfExists('news_types');
  }
}
