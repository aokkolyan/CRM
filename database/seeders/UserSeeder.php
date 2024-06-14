<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Super Admin",
            'user_name' => "admin",
            'email' => 'admin@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>1,
            'branch_id'=>1,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "Some Director",
            'user_name' => "director",
            'email' => 'admin1@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>1,
            'branch_id'=>1,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "Teacher STM",
            'user_name' => "teacher_stm",
            'email' => 'admin12@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>2,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "Hc STM",
            'user_name' => "hc_stm",
            'email' => 'admin13@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>2,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "School Principle STM",
            'user_name' => "sp_stm",
            'email' => 'admfdsin133@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>2,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "Referral Administrator",
            'user_name' => "REFERRAL_ADMINISTRATOR",
            'email' => 'admasdfin1333@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>1,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "CS STM",
            'user_name' => "cs_stm1",
            'email' => 'admfdain1333@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>2,
            'default_password'=>0,
        ]);

        DB::table('users')->insert([
            'name' => "Hc PRL",
            'user_name' => "hc_prl",
            'email' => 'admin13sadf@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>3,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "School Principle PRL",
            'user_name' => "sp_prl",
            'email' => 'admin133322@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>3,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "CS PRL",
            'user_name' => "cs_prl1",
            'email' => 'admin133333@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'branch_id'=>3,
            'school_id'=>2,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "Teacher PRL",
            'user_name' => "teacher_prl",
            'email' => 'admi123n12@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>3,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "CS PRL2",
            'user_name' => "cs_prl2",
            'email' => 'admin133333asd@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'branch_id'=>3,
            'school_id'=>2,
            'default_password'=>0,
        ]);
        DB::table('users')->insert([
            'name' => "CS STM2",
            'user_name' => "cs_stm2",
            'email' => 'admfdainjk1333@gmail.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by'=>1,
            'school_id'=>2,
            'branch_id'=>2,
            'default_password'=>0,
        ]);
    }
}
