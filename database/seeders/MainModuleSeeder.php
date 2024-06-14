<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MainModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_modules')->insert([
            'name' => "CRM",
        ]);
        DB::table('main_modules')->insert([
            'name' => "Referral",
        ]);
        DB::table('main_modules')->insert([
            'name' => "Setting",
        ]);
    }
}
