<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchoolInnovationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_innovations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('title'); // ชื่อนวัตกรรม
            $table->text('description')->nullable(); // รายละเอียดนวัตกรรม
            $table->text('image_path')->nullable(); // เก็บได้ทั้งไฟล์เดี่ยวหรือ JSON ของหลายไฟล์
            $table->string('category')->nullable(); // ประเภทนวัตกรรม
            $table->smallInteger('year')->nullable(); // ปีที่สร้างนวัตกรรม
            $table->boolean('is_active')->default(true); // สถานะการแสดงผล
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
        Schema::dropIfExists('school_innovations');
    }
}
