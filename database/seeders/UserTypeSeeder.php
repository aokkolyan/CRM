<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class UserTypeSeeder extends Seeder
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
                'name_en' => 'Employee',
                'name_kh' => 'បុគ្គលិក',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Parent',
                'name_kh' => 'មាតាបិតា',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Student',
                'name_kh' => 'សិស្ស',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Alumni',
                'name_kh' => 'អតិតសិស្ស',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('user_types')->insert($data);
        $data = [
            [
                'school_id' => '1',
                'user_type_id' => '1',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '1',
                'user_type_id' => '2',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '1',
                'user_type_id' => '3',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '1',
                'user_type_id' => '4',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '2',
                'user_type_id' => '1',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '2',
                'user_type_id' => '2',
                'commission_type' => 'point',
            ],
            [
                'school_id' => '2',
                'user_type_id' => '3',
                'commission_type' => 'point',
            ],
            [
                'school_id' => '2',
                'user_type_id' => '4',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '3',
                'user_type_id' => '1',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '3',
                'user_type_id' => '2',
                'commission_type' => 'point',
            ],
            [
                'school_id' => '3',
                'user_type_id' => '3',
                'commission_type' => 'point',
            ],
            [
                'school_id' => '3',
                'user_type_id' => '4',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '4',
                'user_type_id' => '1',
                'commission_type' => 'cash',
            ],
            [
                'school_id' => '4',
                'user_type_id' => '2',
                'commission_type' => 'point',
            ],
            [
                'school_id' => '4',
                'user_type_id' => '3',
                'commission_type' => 'point',
            ],
            [
                'school_id' => '4',
                'user_type_id' => '4',
                'commission_type' => 'cash',
            ],
        ];
        DB::table('school_user_type_commission_types')->insert($data);
       
    }
}
