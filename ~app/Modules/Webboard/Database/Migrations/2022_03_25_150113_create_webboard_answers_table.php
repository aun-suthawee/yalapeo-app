<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebboardAnswersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('webboard_answers', function (Blueprint $table) {
      // $table->engine = 'MyISAM';
      $table->charset = 'utf8';
      $table->collation = 'utf8_unicode_ci';

      $table->bigIncrements('id');
      $table->string('author')->nullable()->comment('ผู้เขียน');
      $table->ipAddress('ip')->nullable();
      $table->longText('detail')->nullable()->comment('รายละเอียด');

      $table->unsignedBigInteger('webboard_id')->nullable();
      $table->foreign('webboard_id', 'FK_answers_webboards')
        ->references('id')
        ->on('webboards');

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
