<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Pages;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::create([
            'tag' => 'Main',
            'const' => 1,
        ]);

        // footer
        $footer = Menu::create([
            'tag' => 'Footer',
            'const' => 1,
        ]);

        // home
        $menuPage = Menu::create([
            'tag' => $menu->tag,
            'type' => Menu::TYPE_PAGE,
            'model_id' => 1,
        ]);


        $menuPage->translateOrNew('uk')->name = 'Головна';
        $menuPage->translateOrNew('uk')->url = '/';

        $menuPage->translateOrNew('ru')->name = 'Главная';
        $menuPage->translateOrNew('ru')->url = '/ru';

        $menuPage->translateOrNew('en')->name = 'Homepage';
        $menuPage->translateOrNew('en')-> url ='/en';

        $menuPage->save();

        // contacts
        $menuPage = Menu::create([
            'tag' => $menu->tag,
            'type' => Menu::TYPE_PAGE,
            'model_id' => 2,
        ]);


        $menuPage->translateOrNew('uk')->name = 'Контакти';
        $menuPage->translateOrNew('uk')->url = '/contacts';

        $menuPage->translateOrNew('ru')->name = 'Контакты';
        $menuPage->translateOrNew('ru')->url = '/ru/contacts';

        $menuPage->translateOrNew('en')->name = 'Contacts';
        $menuPage->translateOrNew('en')-> url ='/en/contacts';

        $menuPage->save();



        // about
        $menuPage = Menu::create([
            'tag' => $footer->tag,
            'type' => Menu::TYPE_PAGE,
            'model_id' => 3,
        ]);


        $menuPage->translateOrNew('uk')->name = 'Про нас';
        $menuPage->translateOrNew('uk')->url = '/about';

        $menuPage->translateOrNew('ru')->name = 'О нас';
        $menuPage->translateOrNew('ru')->url = '/ru/about';

        $menuPage->translateOrNew('en')->name = 'About us';
        $menuPage->translateOrNew('en')-> url ='/en/about';
        $menuPage->save();

    }
}
