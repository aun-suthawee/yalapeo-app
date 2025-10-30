<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolVisionVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_vision_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('video_url', 500);
            $table->enum('video_type', ['youtube', 'facebook', 'tiktok', 'other'])->default('youtube');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            
            // Index for faster queries
            $table->index(['school_id', 'is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_vision_videos');
    }
}
