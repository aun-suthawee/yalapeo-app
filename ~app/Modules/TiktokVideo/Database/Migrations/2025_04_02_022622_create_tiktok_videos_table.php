<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiktokVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiktok_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('url');
            $table->string('video_id');
            $table->text('detail')->nullable();
            $table->integer('view')->default(0);
            $table->string('slug')->unique()->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('tiktok_videos');
    }
}
