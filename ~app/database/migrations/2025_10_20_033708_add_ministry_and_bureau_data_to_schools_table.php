<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddMinistryAndBureauDataToSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // อัปเดตข้อมูลสังกัดกระทรวงให้ตรงกับค่ามาตรฐาน
        DB::table('schools')->update([
            'ministry_affiliation' => DB::raw("CASE 
                WHEN ministry_affiliation LIKE '%ศึกษาธิการ%' OR ministry_affiliation LIKE '%ศธ.%' THEN 'กระทรวงศึกษาธิการ'
                WHEN ministry_affiliation LIKE '%เอกชน%' THEN 'สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน'
                WHEN ministry_affiliation LIKE '%มหาดไทย%' OR ministry_affiliation LIKE '%อบจ%' OR ministry_affiliation LIKE '%เทศบาล%' THEN 'กระทรวงมหาดไทย'
                ELSE ministry_affiliation 
            END")
        ]);
        
        // อัปเดตข้อมูลสังกัดสำนักงาน/กรม
        DB::table('schools')->update([
            'bureau_affiliation' => DB::raw("CASE 
                WHEN department LIKE '%สำนักงานเขตพื้นที่การศึกษา%' THEN 'สำนักงานคณะกรรมการการศึกษาขั้นพื้นฐาน (สพฐ.)'
                WHEN department LIKE '%สำนักงานส่งเสริมการศึกษาเอกชน%' OR department LIKE '%สช.%' THEN 'สำนักงานการศึกษาเอกชนจังหวัดยะลา'
                WHEN department LIKE '%องค์การบริหารส่วนจังหวัด%' OR department LIKE '%อบจ%' THEN 'องค์การบริหารส่วนจังหวัดยะลา'
                WHEN department LIKE '%เทศบาล%' THEN 'กรมส่งเสริมการปกครองท้องถิ่น'
                ELSE bureau_affiliation 
            END")
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ไม่มีการย้อนกลับเนื่องจากเป็นการอัปเดตข้อมูลเดิม
    }
}
