<?php

namespace Modules\Sandbox\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VisionVideosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $videoUrl = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';

        // Get existing school IDs from 2 to 56
        $existingSchoolIds = DB::table('schools')
            ->whereBetween('id', [2, 56])
            ->pluck('id')
            ->toArray();

        if (empty($existingSchoolIds)) {
            $this->command->warn('⚠️  ไม่พบโรงเรียน ID 2-56 ในฐานข้อมูล');
            return;
        }

        $visionVideos = [];

        foreach ($existingSchoolIds as $schoolId) {
            $visionVideos[] = [
                'school_id' => $schoolId,
                'video_type' => 'youtube',
                'video_url' => $videoUrl,
                'title' => 'วิสัยทัศน์การพัฒนาการศึกษาในศตวรรษที่ 21',
                'description' => 'วิดีโอนี้นำเสนอวิสัยทัศน์และแนวทางการพัฒนาการศึกษาของโรงเรียน เพื่อสร้างนวัตกรรมและยกระดับคุณภาพการเรียนรู้ให้กับนักเรียนในยุคดิจิทัล มุ่งเน้นการพัฒนาทักษะการคิดวิเคราะห์ ความคิดสร้างสรรค์ และการทำงานร่วมกัน เพื่อเตรียมความพร้อมให้นักเรียนเป็นพลเมืองที่มีคุณภาพของสังคมไทยและสังคมโลก',
                'order' => 0,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('school_vision_videos')->insert($visionVideos);

        $this->command->info('✅ สร้างวิดีโอวิสัยทัศน์สำหรับโรงเรียนเรียบร้อยแล้ว (' . count($visionVideos) . ' รายการ)');
        $this->command->info('📌 โรงเรียน ID: ' . implode(', ', $existingSchoolIds));
    }
}
