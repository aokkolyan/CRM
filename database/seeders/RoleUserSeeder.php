<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->insert([
            'user_id' => 1,
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 2,
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 3,
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 4,
            'role_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 5,
            'role_id' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 6,
            'role_id' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 7,
            'role_id' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 8,
            'role_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 9,
            'role_id' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 10,
            'role_id' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 11,
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 12,
            'role_id' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('role_user')->insert([
            'user_id' => 13,
            'role_id' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
