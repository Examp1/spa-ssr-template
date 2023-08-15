<?php

namespace Database\Seeders;

use App\Models\Pages;
use App\Models\Settings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //home
        $page = Pages::firstOrCreate([
            'slug' => '/',
            'status' => 1,
        ]);

        //uk
        $data['uk'] = [
            'title' => 'Головна',
            'meta_title' => 'Головна',
            'meta_description' => 'Головна',
            'status_lang' => 1,
        ];

        //ru
        $data['ru'] = [
            'title' => 'Главная',
            'meta_title' => 'Главная',
            'meta_description' => 'Главная',
            'status_lang' => 1,
        ];

        //en
        $data['en'] = [
            'title' => 'Home',
            'meta_title' => 'Home',
            'meta_description' => 'Home',
            'status_lang' => 1,
        ];


        foreach (config('translatable.locales') as $lang => $item) {
            $trans = $page->translateOrNew($lang);
            $trans->fill($data[$lang]);
        }

        $page->save();

        // contacts
        $page = Pages::firstOrCreate([
            'slug' => 'contacts',
            'status' => 1,
        ]);

        //uk
        $data['uk'] = [
            'title' => 'Контакти',
            'meta_title' => 'Контакти',
            'meta_description' => 'Контакти',
            'status_lang' => 1,
        ];

        //ru
        $data['ru'] = [
            'title' => 'Контакты',
            'meta_title' => 'Контакты',
            'meta_description' => 'Контакты',
            'status_lang' => 1,
        ];

        //en
        $data['en'] = [
            'title' => 'Contacts',
            'meta_title' => 'Contacts',
            'meta_description' => 'Contacts',
            'status_lang' => 1,
        ];

        foreach (config('translatable.locales') as $lang => $item) {
            $trans = $page->translateOrNew($lang);
            $trans->fill($data[$lang]);
        }

        $page->save();

        // about
        $page = Pages::firstOrCreate([
            'slug' => 'about',
            'status' => 1,
        ]);

        //uk
        $data['uk'] = [
            'title' => 'Про нас',
            'meta_title' => 'Про нас',
            'meta_description' => 'Про нас',
            'status_lang' => 1,
        ];

        //ru
        $data['ru'] = [
            'title' => 'О нас',
            'meta_title' => 'О нас',
            'meta_description' => 'О нас',
            'status_lang' => 1,
        ];

        //en
        $data['en'] = [
            'title' => 'About us',
            'meta_title' => 'About us',
            'meta_description' => 'About us',
            'status_lang' => 1,
        ];

        foreach (config('translatable.locales') as $lang => $item) {
            $trans = $page->translateOrNew($lang);
            $trans->fill($data[$lang]);
        }

        $page->save();
    }
}
