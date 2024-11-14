<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GrievanceStatusSeeder extends Seeder
{
    public function run()
    {
        DB::table('grievance_statuses')->insertOrIgnore([
            ['name' => 'open'],
            ['name' => 'closed'],
        ]);
    }
}
