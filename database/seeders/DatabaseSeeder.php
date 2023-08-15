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
        $this->call(LangsSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(SettingSectionSeeder::class);
        // $this->call(MultimediaSeeder::class);
        // $this->call(BlogCategoriesSeeder::class);
        // $this->call(BlogTagsSettingSeeder::class);
    }
}
