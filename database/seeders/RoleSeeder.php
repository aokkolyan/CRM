<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => "Super Admin",
            'value' => "SUPER_ADMIN",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('roles')->insert([
            'name' => "Director",
            'value' => "DIRECTOR",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('roles')->insert([
            'name' => "Employee",
            'value' => "EMPLOYEE",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('roles')->insert([
            'name' => "Head Cashier",
            'value' => "HEAD CASHIER",
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('roles')->insert([
            'name' => "School Principal",
            'value' => "SCHOOL_PRINCIPAL",
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('roles')->insert([
            'name' => "Referral Administrator",
            'value' => "REFERRAL_ADMINISTRATOR",
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('roles')->insert([
            'name' => "Customer Service",
            'value' => "customer_service",
            'created_at' => now(),
            'updated_at' => now()
        ]);
   
    }
}
