<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::insert([
            ['code' => 'main_page_type', 'value'   => 1, 'lang'                => 'uk'],
            ['code' => 'main_page_page_id','value' => 1, 'lang'                => 'uk'],
            ['code' => 'main_page_type', 'value'   => 1, 'lang'                => 'ru'],
            ['code' => 'main_page_page_id','value' => 1, 'lang'                => 'ru'],
            ['code' => 'main_page_type', 'value'   => 1, 'lang'                => 'en'],
            ['code' => 'main_page_page_id','value' => 1, 'lang'                => 'en'],
            ['code' => 'favicon','value'           => '/demo/test.png', 'lang' => 'uk'],
            ['code' => 'default_og_image','value'  => '/demo/test.png', 'lang' => 'uk'],
            ['code' => 'logotype','value'          => '/demo/test.png', 'lang' => 'uk'],
            ['code' => 'logotype_admin','value'    => '/demo/test.png', 'lang' => 'uk'],
            ['code' => 'phones','value'            => '{"1":{"label":"Контакт центр","number":"+ 38 000 123 45 67"}}', 'lang' => 'uk'],
            ['code' => 'links','value'             => '{"1":{"link":"https:\/\/www.facebook.com\/owlweb.com.ua\/","image":"\/demo\/62a335407817a.png"},"2":{"link":"https:\/\/www.instagram.com\/accounts\/login\/?next=\/owlweb_in_web\/","image":"\/demo\/62a3358202bb6.png"},"3":{"link":null,"image":null}}', 'lang' => 'uk'],
        ]);
    }
}
