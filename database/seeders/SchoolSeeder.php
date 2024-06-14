<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name_en' => 'Westline Group',
                'name_kh' => 'វ៉េស្ទឡាញន៍ គ្រុប',
                'code' => 'WEG',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ ',
                'code' => 'WLS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Northline School',
                'name_kh' => "សាលារៀនណត្សឡាញន៍ ",
                'code' => 'NLS',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Leaderland English Academy',
                'name_kh' => 'សាលាភាសាអង់គ្លេសលីដឺលែន',
                'code' => 'LEA',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Foresthill international school',
                'name_kh' => 'សាលាអន្តរជាតិ ហ្វររ៉េសហៀល',
                'code' => 'FHIS',
                'created_at' => now(),
                'updated_at' => now()
            ],
         
            [
                'name_en' => 'Community 21 School',
                'name_kh' => 'សាលាសាហគមន៏២១',
                'code' => 'C21',
                'created_at' => now(),
                'updated_at' => now()
            ],
         ];
         DB::table('schools')->insert($data);
    }
}
