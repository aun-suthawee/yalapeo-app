<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_code', 50)->unique()->nullable();
            $table->string('name'); // ชื่อโรงเรียน
            $table->string('department'); // สังกัด (เช่น ส.ป.ร.)
            $table->string('ministry_affiliation')->nullable(); // สังกัดกระทรวง
            $table->string('bureau_affiliation')->nullable(); // สังกัดสำนัก/กอง
            $table->string('education_area')->nullable(); // เขตพื้นที่การศึกษา
            $table->string('school_type')->nullable(); // ประเภทสถานศึกษา (ขนาด)
            $table->integer('male_students')->default(0); // จำนวนนักเรียนชาย
            $table->integer('female_students')->default(0); // จำนวนนักเรียนหญิง
            $table->integer('male_teachers')->default(0); // จำนวนครูชาย
            $table->integer('female_teachers')->default(0); // จำนวนครูหญิง
            $table->text('address')->nullable(); // ที่อยู่
            $table->string('subdistrict')->nullable(); // ตำบล
            $table->string('district')->nullable(); // อำเภอ
            $table->string('phone')->nullable(); // เบอร์โทร
            $table->string('email')->nullable(); // อีเมล
            $table->decimal('latitude', 10, 8)->nullable(); // ละติจูด
            $table->decimal('longitude', 11, 8)->nullable(); // ลองจิจูด
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
        Schema::dropIfExists('schools');
    }
}
