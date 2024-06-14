<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
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
                'name_en' => 'Khmer Kindergarten Program (3-5 years old)',
                'name_kh' => 'កម្មវិធីមត្តេយ្យភាសាខ្មែរ (អាយុ ៣-៥ឆ្នាំ)',
                'code' => 'KKP',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                
                'name_en' => 'English Kindergarten Program (3-6 years old)',
                'name_kh' => 'កម្មវិធីមត្តេយ្យភាសាអង់គ្លេស (អាយុ ៣-៦ឆ្នាំ)',
                'code' => 'EKP',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],    
            [
                'name_en' => 'Young Learner’s Program (6-12 years old)',
                'name_kh' => 'កម្មវិធីភាសាអង់គ្លេសសម្រាប់កុមារ (អាយុ ៦-១២ឆ្នាំ)',
                'code' => 'YLP',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],   
            [
                'name_en' => 'Play Group Program (2-3 years old)',
                'name_kh' => 'កម្មវិធីអប់រំ និងថែទាំកុមារតូច (អាយុ ២-៣ឆ្នាំ)',
                'code' => 'PGP',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],   
            [
                'name_en' => 'Chinese for Young Learners’ Program (6-12 years old)',
                'name_kh' => 'កម្មវិធីភាសាអង់គ្លេសសម្រាប់មធ្យមសិក្សា (អាយុ ១២-១៨ឆ្នាំ) ',
                'code' => 'CYL',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],   
            [
                'name_en' => 'English for Secondary Program (12-18 years old)',
                'name_kh' => 'កម្មវិធីភាសាចិនសម្រាប់កុមារ (អាយុ ៦-១២ឆ្នាំ) ',
                'code' => 'ESP',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],   
            [
                'name_en' => 'Part-Timer English Program (10-18 years old)',
                'name_kh' => 'កម្មវិធីភាសាអង់គ្លេសក្រៅម៉ោង (អាយុ ១០-១៨ឆ្នាំ)',
                'code' => 'PEP',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],   
            [
                'name_en' => 'Khmer Academic Program (G1-G12)',
                'name_kh' => 'កម្មវិធីចំណេះទូទៅខ្មែរ (ថ្នាក់ទី១-១២)',
                'code' => 'KAP',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],   
        ];
        DB::table('products')->insert($data);
    }
}
