<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            'name' => "Dashboard",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "User",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "Role",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "Permission",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "SideMenu",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "School",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "Branch",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "MyReferral",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "ReferralStatus",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "PaymentStatus",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "Referral",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "Commission",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "Lead",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "ReferralReport",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        DB::table('modules')->insert([
            'name' => "WeeklyReportReferral",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        //16
        DB::table('modules')->insert([
            'name' => "CommissionPayment",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
        //17
        DB::table('modules')->insert([
            'name' => "MapLocation",
            'status'=> 1,
            'is_main'=> 1,
            'have_workflow'=> 0,
            'created_by'=>1,
        ]);
    }
}
