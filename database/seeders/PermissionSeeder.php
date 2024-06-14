<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
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
                'role_id' => 1,
                'module_id' => 1,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 1,
                'module_id' => 2,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 1,
                'module_id' => 3,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 2,
                'module_id' => 1,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 1,
                'module_id' => 8,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            //Teacher Employee
            [
                'role_id' => 3,
                'module_id' => 1,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 3,
                'module_id' => 8,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 3,
                'module_id' => 8,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 3,
                'module_id' => 8,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 3,
                'module_id' => 8,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            //School Accountatnt
            [
                'role_id' => 4,
                'module_id' => 1,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 8,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 8,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 8,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 8,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 15,
                'permission'=>'pay',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // [
            //     'role_id' => 4,
            //     'module_id' => 12,
            //     'permission'=>'viewAny',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'role_id' => 4,
            //     'module_id' => 12,
            //     'permission'=>'view',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'role_id' => 4,
            //     'module_id' => 12,
            //     'permission'=>'create',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            // [
            //     'role_id' => 4,
            //     'module_id' => 12,
            //     'permission'=>'update',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
         
            // [
            //     'role_id' => 4,
            //     'module_id' => 14,
            //     'permission'=>'viewAny',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            [
                'role_id' => 4,
                'module_id' => 15,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 15,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 15,
                'permission'=>'check',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 4,
                'module_id' => 16,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            //SP
            [
                'role_id' => 5,
                'module_id' => 1,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 5,
                'module_id' => 8,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 5,
                'module_id' => 8,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 5,
                'module_id' => 8,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 5,
                'module_id' => 8,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // [
            //     'role_id' => 5,
            //     'module_id' => 14,
            //     'permission'=>'viewAny',
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ],
            [
                'role_id' => 5,
                'module_id' => 15,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 5,
                'module_id' => 15,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 5,
                'module_id' => 15,
                'permission'=>'approve',
                'created_at' => now(),
                'updated_at' => now()
            ],
            //Referral Administrator
            [
                'role_id' => 6,
                'module_id' => 1,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 6,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 6,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 6,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 6,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 7,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 7,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 7,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 7,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 2,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 2,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 2,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 6,
                'module_id' => 2,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            //Customer Service
            [
                'role_id' => 7,
                'module_id' => 1,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 8,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 8,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 8,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 8,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 11,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 11,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 11,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 11,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 15,
                'permission'=>'viewAny',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 15,
                'permission'=>'view',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 15,
                'permission'=>'create',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'role_id' => 7,
                'module_id' => 15,
                'permission'=>'update',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
         ];
         DB::table('permissions')->insert($data);
    }
}
