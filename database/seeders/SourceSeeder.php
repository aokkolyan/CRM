<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class SourceSeeder extends Seeder
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
                'name_en' => 'Social Media',
                'name_kh' => 'បញ្តាញសង្គម ',
                'code' => '',
                'color'=>'#34568B',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Email MArketing',
                'name_kh' => "Email MArketing ",
                'code' => '',
                'color'=>'#FF6F61',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Organic Search',
                'name_kh' => 'Organic Search',
                'code' => '',
                'color'=>'#6B5B95',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Paid Social',
                'name_kh' => 'Paid Social',
                'code' => '',
                'color'=>'#88B04B',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Paid Seach',
                'name_kh' => 'Paid Seach',
                'code' => '',
                'color'=>'#F7CAC9',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Referral',
                'name_kh' => 'Referral',
                'code' => '',
                'color'=>'#92A8D1',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Direct Traffic',
                'name_kh' => 'Direct Traffic',
                'code' => '',
                'color'=>'#955251',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Offline Sources',
                'name_kh' => 'Offline Sources',
                'code' => '',
                'color'=>'#DD4124',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Cold Call',
                'name_kh' => 'Cold Call',
                'code' => '',
                'color'=>'#45B8AC',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Other',
                'name_kh' => 'Other',
                'code' => '',
                'color'=>'#ffab00',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('sources')->insert($data);
    }
}
