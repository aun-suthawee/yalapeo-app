<?php

namespace Modules\Sandbox\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Modules\Sandbox\Entities\School;
use Modules\Sandbox\Entities\SchoolInnovation;

class SchoolInnovationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('th_TH');
        $categories = [
            'ด้านการบริหารจัดการ',
            'ด้านหลักสูตรสถานศึกษา',
            'ด้านการจัดการเรียนรู้',
            'ด้านการนิเทศการศึกษา',
            'ด้านสื่อเทคโนโลยี',
            'ด้านการประกันคุณภาพการศึกษา',
            'ด้านการวัดและประเมินผล',
            'ด้านอื่น ๆ',
        ];
        $titlePrefixes = ['นวัตกรรม', 'โครงการ', 'โมเดล', 'แนวทาง'];
        $titleThemes = [
            'การเรียนรู้เชิงรุก',
            'พัฒนาสมรรถนะผู้เรียน',
            'การใช้สื่อดิจิทัล',
            'การบูรณาการชุมชน',
            'การจัดการชั้นเรียน',
            'การดูแลช่วยเหลือนักเรียน',
            'การนิเทศภายในโรงเรียน',
            'การเรียนรู้แบบฐานสมรรถนะ',
        ];

        $schools = School::whereBetween('id', [2, 56])->get();

        if ($schools->isEmpty()) {
            $this->command?->warn('ไม่พบข้อมูลโรงเรียนในช่วง ID 2-56 จึงไม่ได้ทำการเพิ่มข้อมูลนวัตกรรม');
            return;
        }

        foreach ($schools as $school) {
            $existingTitles = $school->innovations()->pluck('title')->map(fn ($title) => Str::lower($title))->all();
            $innovationCount = $faker->numberBetween(1, 3);

            for ($i = 0; $i < $innovationCount; $i++) {
                $title = $this->generateUniqueTitle($titlePrefixes, $titleThemes, $existingTitles, $faker);
                $category = Arr::random($categories);

                SchoolInnovation::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'title' => $title,
                    ],
                    [
                        'description' => $faker->paragraphs($faker->numberBetween(2, 4), true),
                        'category' => $category,
                        'year' => $faker->numberBetween(2555, 2568),
                        'is_active' => $faker->boolean(80),
                        'image_path' => null,
                        'created_at' => now()->subDays($faker->numberBetween(30, 720)),
                        'updated_at' => now()->subDays($faker->numberBetween(1, 15)),
                    ]
                );

                $existingTitles[] = Str::lower($title);
            }
        }
    }

    /**
     * Generate a unique, human-friendly innovation title.
     */
    protected function generateUniqueTitle(array $prefixes, array $themes, array $existingTitles, \Faker\Generator $faker): string
    {
        $attempt = 0;

        do {
            $prefix = Arr::random($prefixes);
            $theme = Arr::random($themes);
            $descriptor = $faker->words($faker->numberBetween(1, 2), true);
            $title = trim(sprintf('%s %s %s', $prefix, $theme, Str::title($descriptor)));
            $attempt++;
        } while (in_array(Str::lower($title), $existingTitles, true) && $attempt < 10);

        if ($attempt >= 10) {
            $title = sprintf('นวัตกรรมเฉพาะทาง %s', Str::upper(Str::random(6)));
        }

        return $title;
    }
}
