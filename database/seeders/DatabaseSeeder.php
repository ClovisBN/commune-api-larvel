<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SurveyStatusSeeder;
use Database\Seeders\ArticleStatusSeeder;
use Database\Seeders\GrievanceStatusSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SurveyStatusSeeder::class,
            GrievanceStatusSeeder::class,
            ArticleStatusSeeder::class,

        ]);
    }
}
