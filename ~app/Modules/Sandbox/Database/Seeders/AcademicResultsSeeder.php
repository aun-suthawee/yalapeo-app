<?php

namespace Modules\Sandbox\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\AcademicResult;

class AcademicResultsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $year = 2025; // ปีการศึกษา (ค.ศ.)
        
        // ดึงโรงเรียนทั้งหมด
        $schools = School::all();
        
        $this->command->info("🎯 กำลังสร้างข้อมูลคะแนนสำหรับ " . $schools->count() . " โรงเรียน...");
        
        $created = 0;
        
        foreach ($schools as $school) {
            // ตรวจสอบว่ามีข้อมูลอยู่แล้วหรือไม่
            $existing = AcademicResult::where('school_id', $school->id)
                ->where('academic_year', $year)
                ->first();
            
            if ($existing) {
                continue; // ข้ามถ้ามีข้อมูลแล้ว
            }
            
            // กำหนดว่าโรงเรียนมีการสอบอะไรบ้าง (สุ่ม)
            // โรงเรียนส่วนใหญ่มีทั้ง 3 ประเภท บางโรงเรียนอาจมีแค่บางประเภท
            $hasNT = rand(1, 100) > 10; // 90% มี NT
            $hasRT = rand(1, 100) > 15; // 85% มี RT
            $hasONET = rand(1, 100) > 20; // 80% มี O-NET
            
            // สร้างข้อมูลคะแนน
            AcademicResult::create([
                'school_id' => $school->id,
                'academic_year' => $year,
                
                // Test availability flags
                'has_nt_test' => $hasNT,
                'has_rt_test' => $hasRT,
                'has_onet_test' => $hasONET,
                
                // NT Scores (ป.3) - คะแนนเฉลี่ย 40-85
                'nt_math_score' => $hasNT ? $this->generateScore(40, 85) : null,
                'nt_thai_score' => $hasNT ? $this->generateScore(45, 82) : null,
                
                // RT Scores (ป.1) - คะแนนเฉลี่ย 50-90
                'rt_reading_score' => $hasRT ? $this->generateScore(50, 90) : null,
                'rt_comprehension_score' => $hasRT ? $this->generateScore(48, 87) : null,
                
                // O-NET Scores (ป.6/ม.3) - คะแนนเฉลี่ย 30-75
                'onet_math_score' => $hasONET ? $this->generateScore(30, 75) : null,
                'onet_thai_score' => $hasONET ? $this->generateScore(35, 78) : null,
                'onet_english_score' => $hasONET ? $this->generateScore(28, 70) : null,
                'onet_science_score' => $hasONET ? $this->generateScore(32, 76) : null,
                
                // Metadata
                'notes' => $this->generateNotes(),
                'submitted_at' => rand(1, 100) > 5 ? now()->subDays(rand(1, 30)) : null // 95% ส่งแล้ว
            ]);
            
            $created++;
        }
        
        $this->command->info("✅ สร้างข้อมูลคะแนนสำเร็จ {$created} โรงเรียน");
    }
    
    /**
     * สร้างคะแนนแบบสุ่มตามช่วงที่กำหนด
     */
    private function generateScore($min, $max)
    {
        // สร้างคะแนนแบบ normal distribution (ส่วนใหญ่อยู่ตรงกลาง)
        $mean = ($min + $max) / 2;
        $std = ($max - $min) / 4;
        
        $score = $this->normalRandom($mean, $std);
        
        // จำกัดไม่ให้เกินขอบเขต
        $score = max($min, min($max, $score));
        
        return round($score, 2);
    }
    
    /**
     * สร้างตัวเลขสุ่มแบบ Normal Distribution
     */
    private function normalRandom($mean, $std)
    {
        $u1 = mt_rand() / mt_getrandmax();
        $u2 = mt_rand() / mt_getrandmax();
        
        $z0 = sqrt(-2.0 * log($u1)) * cos(2.0 * M_PI * $u2);
        
        return $mean + $std * $z0;
    }
    
    /**
     * สร้างหมายเหตุแบบสุ่ม
     */
    private function generateNotes()
    {
        $notes = [
            'ส่งข้อมูลครบถ้วน',
            'นักเรียนมีความตั้งใจในการเรียน',
            'ผลการสอบดีกว่าปีที่แล้ว',
            'ต้องปรับปรุงการสอนวิชาคณิตศาสตร์',
            'นักเรียนมีพื้นฐานที่แตกต่างกัน',
            'ควรเพิ่มชั่วโมงสอนภาษาอังกฤษ',
            null, // บางโรงเรียนไม่มีหมายเหตุ
            null,
            null
        ];
        
        return $notes[array_rand($notes)];
    }
}
