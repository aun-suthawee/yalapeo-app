<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsmLpasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('asm_lpas', function (Blueprint $table) {
      $table->engine = 'MyISAM';
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('year');
      $table->longText('lpas')->nullable()->comment('ข้อมูล Lpa');

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
    Schema::dropIfExists('asm_lpas');
  }
}
