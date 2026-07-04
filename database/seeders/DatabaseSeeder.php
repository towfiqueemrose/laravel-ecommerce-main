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
        $this->call(RolePermissionSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(UserRolePermissionSeeder::class);
        $this->call(BasicinfoSeeder::class);
        $this->call(DemoDataSeeder::class);
        // $this->call(InformationSeeder::class);
        // $this->call(UserSeeder::class);
        // \App\Models\User::factory(10)->create();
        // \App\Models\Addbanner::factory(4)->create();

    }
}