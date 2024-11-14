<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveyStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('survey_statuses')->insertOrIgnore([
            ['name' => 'unpublished'],
            ['name' => 'published'],
            ['name' => 'closed'],
        ]);
    }
}
