<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('article_statuses')->insertOrIgnore([
            ['name' => 'unpublished'],
            ['name' => 'published'],
        ]);
    }
}
