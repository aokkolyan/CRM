<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class StageSeeder extends Seeder
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
                'name_en' => 'Qualificaion',
                'name_kh' => 'Qualificaion ',
                'code' => '',
                'status_id'=>1,
                'color'=>'#ffab00',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Proposal',
                'name_kh' => 'Proposal ',
                'code' => '',
                'status_id'=>1,
                'color'=>'#2f9254',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Negotiating',
                'name_kh' => 'Negotiating ',
                'code' => '',
                'status_id'=>1,
                'color'=>'#457725',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Closed-Won',
                'name_kh' => 'Closed-Won ',
                'code' => '',
                'status_id'=>2,
                'color'=>'#00477e',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Closed-Lost',
                'name_kh' => 'Closed-Lost',
                'code' => '',
                'status_id'=>3,
                'color'=>'#5c5c5b',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('stages')->insert($data);
    }
}
