<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('news', function (Blueprint $table) {
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('title')->nullable();
      $table->string('slug')->nullable();
      $table->text('description')->nullable();
      $table->string('url')->nullable();
      $table->string('target')->nullable()->default('_blank');
      $table->date('date')->nullable();
      $table->longText('detail')->nullable();
      $table->string('cover')->nullable();
      $table->integer('view')->default(10);
      $table->longText('attach')->nullable()->comment('ไฟล์แนบ');

      $table->unsignedBigInteger('type_id')->nullable();
      $table->foreign('type_id', 'FK_type_news')
        ->references('id')
        ->on('news_types');

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
    Schema::dropIfExists('news');
  }
}
