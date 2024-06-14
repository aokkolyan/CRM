<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SchoolSeeder::class,
            BranchSeeder::class,
            UserTypeSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RoleUserSeeder::class,
            ModuleSeeder::class,
            MainModuleSeeder::class,
            SidemenuSeeder::class,
            StatusSeeder::class,
            StageSeeder::class,
            SourceSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}
