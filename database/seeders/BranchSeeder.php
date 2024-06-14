<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class BranchSeeder extends Seeder
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
                'name_en' => 'IT Department',
                'name_kh' => 'IT Department',
                'code' => 'ITD',
                'school_id'=>1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline Santhormok',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍សន្ធរមុខ',
                'code' => 'STM',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline Prek Leap',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ព្រែកលាព',
                'code' => 'PRL',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Northline Stueng Mean Chey',
                'name_kh' => "សាលារៀនណត្សឡាញន៍ស្ទឹងមានជ័យ",
                'code' => 'SMC',
                'school_id'=>3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Foresthill International School Bueng Reung',
                'name_kh' => 'សាលាអន្តរជាតិ ហ្វររ៉េសហៀលបឹងរាំង',
                'code' => 'FHIS',
                'school_id'=>4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Leaderland English Academy',
                'name_kh' => 'សាលាភាសាអង់គ្លេសលីដឺលែន',
                'code' => 'CAP',
                'school_id'=>5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'Account And Finance Department',
                'name_kh' => 'នាយកដ្ឋានគណនេយ្យនិងហិរញ្ញវត្ថុ',
                'code' => 'AFD',
                'school_id'=>1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Tuol Tompoung Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខា​ទួលទំពូង ',
                'code' => 'TTP',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Toul Svay Prey Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខា​ទួលស្វាយព្រៃ',
                'code' => 'TSP',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Phsar Touch Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខាផ្សារតូច',
                'code' => 'PST',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Daun Penh Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខាដូនពេញ',
                'code' => 'DNP',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Chamkar Doung Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខាចំការដូង',
                'code' => 'CKD',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Phnom Penh Thmei Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខាភ្នំពេញថ្មី',
                'code' => 'PPT',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Chbar Ampeou Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខាច្បារអំពៅ',
                'code' => 'CAP',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en' => 'The Westline School, Chamkar Samraong Branch',
                'name_kh' => 'សាលារៀនវ៉េស្ទឡាញន៍ សាខាចំការសំរោង',
                'code' => 'CKS',
                'school_id'=>2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // [
            //     'name_en' => 'Community School 21 Prek Anchanh Branch',
            //     'name_kh' => 'សាលារៀនសហគមន៍២១ សាខាព្រែកអញ្ចាញ',
            //     'code' => 'PAC',
            //     'school_id'=>6,
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'name_en' => 'Community School 21 Kraing Thnong Branch',
            //     'name_kh' => 'សាលារៀនសហគមន៍២១ សាខាក្រាំងធ្នង់',
            //     'code' => 'KRT',
            //     'school_id'=>6,
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'name_en' => 'Community School 21 Kokir Branch',
            //     'name_kh' => 'សាលារៀនសហគមន៍២១ សាខាផ្សាគរគី',
            //     'code' => 'PKK',
            //     'school_id'=>6,
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],

         ];
         DB::table('branches')->insert($data);
    }
}
