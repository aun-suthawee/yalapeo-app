<?php

namespace Modules\Sandbox\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Sandbox\Entities\School;
use Faker\Factory as Faker;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('th_TH');

        $ministries = [
            'กระทรวงศึกษาธิการ',
            'สำนักงานคณะกรรมการส่งเสริมการศึกษาเอกชน',
            'กระทรวงมหาดไทย'
        ];

        $bureaus = [
            'สพฐ.',
            'สำนักงานการศึกษาเอกชนจังหวัดยะลา',
            'กรมส่งเสริมการปกครองท้องถิ่น',
            'องค์การบริหารส่วนจังหวัดยะลา'
        ];

        $departments = [
            'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 1',
            'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 2',
            'สำนักงานเขตพื้นที่การศึกษาประถมศึกษายะลา เขต 3',
            'สำนักงานส่งเสริมการศึกษาเอกชน',
            'เทศบาลนครยะลา',
            'องค์การบริหารส่วนจังหวัดยะลา'
        ];

        $educationAreas = [
            'สพป.ยะลา เขต 1',
            'สพป.ยะลา เขต 2',
            'สพป.ยะลา เขต 3'
        ];

        $schoolTypes = [
            'ขนาดเล็ก (นักเรียนไม่เกิน 120 คน)',
            'ขนาดกลาง (นักเรียน 121-600 คน)',
            'ขนาดใหญ่ (นักเรียน 601-1,500 คน)',
            'ขนาดใหญ่พิเศษ (นักเรียนมากกว่า 1,500 คน)'
        ];

        $districts = [
            'เมืองยะลา',
            'เบตง',
            'บันนังสตา',
            'ธารโต',
            'ยะหา',
            'รามัน',
            'กาบัง',
            'กรงปินัง'
        ];

        $subdistricts = [
            'สะเตง' => 'เมืองยะลา',
            'ยุโป' => 'เมืองยะลา',
            'ลำใหม่' => 'เมืองยะลา',
            'หน้าถ้ำ' => 'เมืองยะลา',
            'เบตง' => 'เบตง',
            'ยะรม' => 'เบตง',
            'ตาเนาะปูเต๊ะ' => 'เบตง',
            'บันนังสตา' => 'บันนังสตา',
            'บาเจาะ' => 'บันนังสตา',
            'ธารโต' => 'ธารโต',
            'บ้านแหร' => 'ธารโต',
            'ยะหา' => 'ยะหา',
            'กาตอง' => 'ยะหา',
            'รามัน' => 'รามัน',
            'กาแยม' => 'กาบัง',
            'กรงปินัง' => 'กรงปินัง'
        ];

        $schoolNames = [
            'บ้าน', 'วัด', 'อนุบาล', 'ชุมชน', 'เทศบาล', 'ราษฎร์',
            'พระราช', 'สาธิต', 'สังกัด', 'เฉลิมพระเกียรติ'
        ];

        $schoolSuffixes = [
            'วิทยา', 'ศึกษา', 'พัฒนา', 'สามัคคี', 'ร่วมใจ',
            'อนุสรณ์', 'วิทยาคาร', 'พิทยาคม', 'วิทยาลัย'
        ];

        // สร้างโรงเรียน 54 โรง
        for ($i = 1; $i <= 54; $i++) {
            // สุ่มข้อมูล
            $ministry = $faker->randomElement($ministries);
            $bureau = $faker->randomElement($bureaus);
            $department = $faker->randomElement($departments);
            $educationArea = $faker->randomElement($educationAreas);
            $district = $faker->randomElement($districts);
            
            // หา subdistrict ที่ตรงกับ district
            $availableSubdistricts = array_keys(array_filter($subdistricts, function($d) use ($district) {
                return $d === $district;
            }));
            $subdistrict = $faker->randomElement($availableSubdistricts);

            // สุ่มชื่อโรงเรียน
            $schoolName = 'โรงเรียน' . $faker->randomElement($schoolNames) . $faker->randomElement($schoolSuffixes);
            if ($faker->boolean(30)) {
                $schoolName .= ' ' . $district;
            }

            // คำนวณจำนวนนักเรียนและครูตามขนาดโรงเรียน
            $schoolType = $faker->randomElement($schoolTypes);
            switch ($schoolType) {
                case 'ขนาดเล็ก (นักเรียนไม่เกิน 120 คน)':
                    $totalStudents = $faker->numberBetween(40, 120);
                    break;
                case 'ขนาดกลาง (นักเรียน 121-600 คน)':
                    $totalStudents = $faker->numberBetween(121, 600);
                    break;
                case 'ขนาดใหญ่ (นักเรียน 601-1,500 คน)':
                    $totalStudents = $faker->numberBetween(601, 1500);
                    break;
                default: // ขนาดใหญ่พิเศษ
                    $totalStudents = $faker->numberBetween(1501, 3000);
                    break;
            }

            $maleStudents = (int)($totalStudents * $faker->randomFloat(2, 0.45, 0.55));
            $femaleStudents = $totalStudents - $maleStudents;

            // คำนวณจำนวนครู (อัตราส่วน 1:20 โดยประมาณ)
            $totalTeachers = (int)($totalStudents / $faker->numberBetween(18, 25));
            $maleTeachers = (int)($totalTeachers * $faker->randomFloat(2, 0.25, 0.45)); // ครูชายน้อยกว่า
            $femaleTeachers = $totalTeachers - $maleTeachers;

            // สร้าง latitude/longitude ในจังหวัดยะลา
            $latitude = $faker->latitude(5.8, 6.9); // พื้นที่จังหวัดยะลา
            $longitude = $faker->longitude(100.8, 101.8);

            School::create([
                'school_code' => sprintf('YL%02d%04d', $faker->numberBetween(1, 8), $i),
                'name' => $schoolName,
                'department' => $department,
                'ministry_affiliation' => $ministry,
                'bureau_affiliation' => $bureau,
                'education_area' => $educationArea,
                'school_type' => $schoolType,
                'male_students' => $maleStudents,
                'female_students' => $femaleStudents,
                'male_teachers' => $maleTeachers,
                'female_teachers' => $femaleTeachers,
                'address' => $faker->streetAddress . ' หมู่ ' . $faker->numberBetween(1, 12),
                'subdistrict' => $subdistrict,
                'district' => $district,
                'phone' => $faker->regexify('0[7-9][0-9]{8}'), // เบอร์มือถือไทย
                'email' => strtolower(str_replace(' ', '', $schoolName)) . '@school.ac.th',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Seeded 54 schools with complete data!');
    }
}
