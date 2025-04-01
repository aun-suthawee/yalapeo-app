<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalTreesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('personal_trees', function (Blueprint $table) {
      // $table->engine = 'MyISAM';
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('title');
      $table->string('position')->nullable();
      $table->string('description')->nullable();
      $table->string('email')->nullable();
      $table->string('tel')->nullable();
      $table->integer('sequent_row')->nullable()->comment('ตำแหน่ง/แถว');
      $table->integer('sequent_col')->nullable()->comment('ตำแหน่ง/คอลัมน์');
      $table->string('cover')->nullable();

      $table->unsignedBigInteger('personal_id')->nullable();
      $table->foreign('personal_id', 'FK_trees_personals')
        ->references('id')
        ->on('personals');

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
    Schema::dropIfExists('personal_trees');
  }
}
