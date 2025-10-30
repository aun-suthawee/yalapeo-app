<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->year('academic_year'); // ปีการศึกษา พ.ศ.
            
            // Test availability flags
            $table->boolean('has_nt_test')->default(true);
            $table->boolean('has_rt_test')->default(true);
            $table->boolean('has_onet_test')->default(true);
            
            // NT Scores (ป.3)
            $table->decimal('nt_math_score', 5, 2)->nullable(); // คะแนนเฉลี่ยคณิต
            $table->decimal('nt_thai_score', 5, 2)->nullable(); // คะแนนเฉลี่ยภาษาไทย
            
            // RT Scores (ป.1)
            $table->decimal('rt_reading_score', 5, 2)->nullable(); // การอ่านออกเสียง
            $table->decimal('rt_comprehension_score', 5, 2)->nullable(); // การอ่านรู้เรื่อง
            
            // O-NET Scores (ป.6 หรือ ม.3)
            $table->decimal('onet_math_score', 5, 2)->nullable(); // คะแนนเฉลี่ยคณิต
            $table->decimal('onet_thai_score', 5, 2)->nullable(); // คะแนนเฉลี่ยภาษาไทย
            $table->decimal('onet_english_score', 5, 2)->nullable(); // คะแนนเฉลี่ยอังกฤษ
            $table->decimal('onet_science_score', 5, 2)->nullable(); // คะแนนเฉลี่ยวิทยาศาสตร์
            
            // Metadata
            $table->text('notes')->nullable(); // หมายเหตุ
            $table->timestamp('submitted_at')->nullable(); // วันที่กรอกข้อมูล
            $table->timestamps();
            
            // Unique constraint: one record per school per year
            $table->unique(['school_id', 'academic_year']);
            
            // Indexes
            $table->index('academic_year');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academic_results');
    }
}
