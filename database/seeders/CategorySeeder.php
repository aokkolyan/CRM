<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
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
                'name_en' => 'Program',
                'name_kh' => 'កម្មវិធីសិក្សា ',
                'code' => '',
                'created_at' => now(),
                'updated_at' => now()
            ],
    
        ];
        DB::table('categories')->insert($data);
    }
}
