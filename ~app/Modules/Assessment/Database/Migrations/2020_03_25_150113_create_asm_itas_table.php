<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsmItasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('asm_itas', function (Blueprint $table) {
      $table->engine = 'MyISAM';
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('year');
      $table->longText('itas')->nullable()->comment('ข้อมูล ITA url');

      $table->unique('year', 'unique_year');

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
    Schema::dropIfExists('asm_itas');
  }
}
