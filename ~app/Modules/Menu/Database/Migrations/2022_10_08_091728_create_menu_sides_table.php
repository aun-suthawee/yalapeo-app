<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuSidesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('menu_sides', function (Blueprint $table) {
      // $table->engine = 'MyISAM';
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('parent_id')->nullable();
      $table->string('name')->nullable();
      $table->string('slug')->nullable();
      $table->string('url')->nullable();
      $table->string('target')->nullable()->default('_blank');
      $table->longText('detail')->nullable()->comment('รายละเอียด');
      $table->longText('attach')->nullable()->comment('ไฟล์แนบ');
      $table->json('sort')->nullable();

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
    Schema::dropIfExists('menu_sides');
  }
}
