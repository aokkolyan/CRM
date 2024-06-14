<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class StatusSeeder extends Seeder
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
                'name_en' => 'Open',
                'name_kh' => 'Open ',
                'code' => '',
                'color'=>'#004d33',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Lost',
                'name_kh' => "Lost ",
                'code' => '',
                'color'=>'#5c5c5b',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Won',
                'name_kh' => 'Won',
                'code' => '',
                'color'=>'#00477e',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('statuses')->insert($data);
        $data = [
            [
                'name_en' => 'Lead',
                'name_kh' => 'គោលដៅ ',
                'code' => '',
                'color'=>'#009688',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Discussion',
                'name_kh' => "កំពុងទាក់ទង ",
                'code' => '',
                'color'=>'#FF9800',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Rejected',
                'name_kh' => 'បដិសេធ',
                'code' => '',
                'color'=>'#F44336',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Enrolled',
                'name_kh' => 'បានចុះឈ្មោះ',
                'code' => '',
                'color'=>'#4CAF50',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('referral_statuses')->insert($data);
        $data = [
     
            [
                'name_en' => 'Unpaid',
                'name_kh' => 'មិនទាន់បង់',
                'code' => '',
                'color'=>'#F44336',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Paid',
                'name_kh' => 'បង់រួច',
                'code' => '',
                'color'=>'#4CAF50',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        DB::table('payment_statuses')->insert($data);
    }
}
