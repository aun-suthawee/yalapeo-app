<?php

namespace Modules\Sandbox\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SandboxDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            SchoolsTableSeeder::class,
            SchoolInnovationsSeeder::class,
            VisionVideosSeeder::class,
            SchoolLocationSeeder::class,
            AcademicResultsSeeder::class,
        ]);
    }
}
