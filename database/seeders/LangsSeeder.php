<?php

namespace Database\Seeders;
use App\Models\Langs;
use Illuminate\Database\Seeder;

class LangsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Langs::insert([
            ['id' => 1, 'name' => 'Українська', 'short_name' => 'UA', 'code' => 'uk', 'default' => true],
            ['id' => 2, 'name' => 'Русский', 'short_name' => 'RU', 'code' => 'ru', 'default' => false],
            ['id' => 3, 'name' => 'English', 'short_name' => 'EN', 'code' => 'en', 'default' => false],
        ]);
    }
}
