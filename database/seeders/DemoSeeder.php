<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Pages;
use App\Models\Settings;
use App\Service\Adapter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * @var Adapter
     */
    private Adapter $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pages

        $this->home();
        $this->contacts();
        $this->about();

        // Settings
        Settings::insert([
            ['code' => 'contacts', 'value' => '[{"is_main":"1","name":"Адреса в  Києві","address":"33-B Bulvarno-Kudryavskaya STR., Kiev, 01054, Ukraine","email":"email.example@gmail.com","phones":[{"label":"+38050 000 00 00","number":"+38050 000 00 00"},{"label":"факс","number":"044 000 00 00"}],"schedule":[{"label":"ПН-ПТ","time":"10-00 до 18-00"}],"socials":[{"link":"https:\/\/www.facebook.com\/","image":"\/demo\/62eae0bb27c27.svg"},{"link":"https:\/\/www.instagram.com\/","image":"\/demo\/62eae0bb28bcd.svg"}],"maps_marks":[{"lat":"50.4503757","lng":"30.4957514"}],"messengers":[{"type":"facebook_messenger","link":"https:\/\/www.messenger.com\/t\/00000000000000","phone":"00000000000000"},{"type":"telegram","link":"tg:\/\/resolve?domain=nickname","phone":"nickname"},{"type":"whats_app","link":"https:\/\/api.whatsapp.com\/send?phone=380931234567","phone":"380931234567"},{"type":"viber","link":"viber:\/\/chat?number=%111111111111","phone":"380931234567"}]},{"name":"Адреса у Львові","address":"проспект Свободи, 28, Львів, Львівська область, УкраЇна, 79000","email":"example@gmail.com","phones":[{"label":"8 800 555 35345","number":"8 800 555 35345"}],"schedule":[{"label":"вт-пт","time":"10-00 до 18-00"},{"label":"сб","time":"10-00 до 15-00"},{"label":"вс","time":"Вихідний"}],"socials":null,"maps_marks":[{"lat":"49.841525168329","lng":"24.027683110360165"}],"messengers":null}]', 'lang' => 'uk'],
            ['code' => 'contacts', 'value' => '[{"is_main":"1","name":"Адреса в  Києві","address":"33-B Bulvarno-Kudryavskaya STR., Kiev, 01054, Ukraine","email":"email.example@gmail.com","phones":[{"label":"+38050 000 00 00","number":"+38050 000 00 00"},{"label":"факс","number":"044 000 00 00"}],"schedule":[{"label":"ПН-ПТ","time":"10-00 до 18-00"}],"socials":[{"link":"https:\/\/www.facebook.com\/","image":"\/demo\/62eae0bb27c27.svg"},{"link":"https:\/\/www.instagram.com\/","image":"\/demo\/62eae0bb28bcd.svg"}],"maps_marks":[{"lat":"50.4503757","lng":"30.4957514"}],"messengers":[{"type":"facebook_messenger","link":"https:\/\/www.messenger.com\/t\/00000000000000","phone":"00000000000000"},{"type":"telegram","link":"tg:\/\/resolve?domain=nickname","phone":"nickname"},{"type":"whats_app","link":"https:\/\/api.whatsapp.com\/send?phone=380931234567","phone":"380931234567"},{"type":"viber","link":"viber:\/\/chat?number=%111111111111","phone":"380931234567"}]},{"name":"Адреса у Львові","address":"проспект Свободи, 28, Львів, Львівська область, УкраЇна, 79000","email":"example@gmail.com","phones":[{"label":"8 800 555 35345","number":"8 800 555 35345"}],"schedule":[{"label":"вт-пт","time":"10-00 до 18-00"},{"label":"сб","time":"10-00 до 15-00"},{"label":"вс","time":"Вихідний"}],"socials":null,"maps_marks":[{"lat":"49.841525168329","lng":"24.027683110360165"}],"messengers":null}]', 'lang' => 'ru'],
            ['code' => 'contacts', 'value' => '[{"is_main":"1","name":"Адреса в  Києві","address":"33-B Bulvarno-Kudryavskaya STR., Kiev, 01054, Ukraine","email":"email.example@gmail.com","phones":[{"label":"+38050 000 00 00","number":"+38050 000 00 00"},{"label":"факс","number":"044 000 00 00"}],"schedule":[{"label":"ПН-ПТ","time":"10-00 до 18-00"}],"socials":[{"link":"https:\/\/www.facebook.com\/","image":"\/demo\/62eae0bb27c27.svg"},{"link":"https:\/\/www.instagram.com\/","image":"\/demo\/62eae0bb28bcd.svg"}],"maps_marks":[{"lat":"50.4503757","lng":"30.4957514"}],"messengers":[{"type":"facebook_messenger","link":"https:\/\/www.messenger.com\/t\/00000000000000","phone":"00000000000000"},{"type":"telegram","link":"tg:\/\/resolve?domain=nickname","phone":"nickname"},{"type":"whats_app","link":"https:\/\/api.whatsapp.com\/send?phone=380931234567","phone":"380931234567"},{"type":"viber","link":"viber:\/\/chat?number=%111111111111","phone":"380931234567"}]},{"name":"Адреса у Львові","address":"проспект Свободи, 28, Львів, Львівська область, УкраЇна, 79000","email":"example@gmail.com","phones":[{"label":"8 800 555 35345","number":"8 800 555 35345"}],"schedule":[{"label":"вт-пт","time":"10-00 до 18-00"},{"label":"сб","time":"10-00 до 15-00"},{"label":"вс","time":"Вихідний"}],"socials":null,"maps_marks":[{"lat":"49.841525168329","lng":"24.027683110360165"}],"messengers":null}]', 'lang' => 'en'],
        ]);

        // Menu
        // $menu = Menu::create([
        //     'tag' => 'Main',
        //     'const' => 1,
        // ]);

        // $menuPage = Menu::create([
        //     'tag' => 'Main',
        //     'type' => Menu::TYPE_PAGE,
        //     'model_id' => Pages::first()->id,
        // ]);

        // $menuPage->translateOrNew('uk')->name = 'Головна';
        // $menuPage->translateOrNew('uk')->url = '/';

        // $menuPage->translateOrNew('ru')->name = 'Главная';
        // $menuPage->translateOrNew('ru')->url = '/';

        // $menuPage->translateOrNew('en')->name = 'Homepage';
        // $menuPage->translateOrNew('en')-> url ='/';
        // $menuPage->save();
    }

    private function home()
    {

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

        $constructor['uk'] = array (
            2 => array (
                'visibility' => '1',
                'position' => '3',
                'component' => 'simple-text',
                'content' => array (
                    'title' => 'Наша розробка',
                    'description' => '<p>Конструктор сторінок-це функціональність, яку створила наша команда, з метою спрощення роботи адміністратора сайту над створенням сторінок, при цьому роблячи їх унікальними.&nbsp;</p><p>Застосування конструктора для таких важливих сторінок, як послуги, продукти, сприяє збільшення показників часу знаходження на сторінці, поліпшення сприйняття контенту, збільшення глибини перегляду сторінки. Що в результаті прямо пропорційно позначається на якості та кількості продажів через сайт.</p><p>Розробляючи, ми відштовхувалися від розуміння зручності для клієнтів замовника і груп користувачів, які будуть працювати з проектом, і сучасних трендів розробки.</p><p><b>І найголовніше - наповнення сторінок не займає багато часу і не вимагає знань програмування.</b></p><p><b>Для демонстрації можливостей блоків ми створили текстову сторінку та лендінг, побудовані на конструкторі.&nbsp;</b></p>',
                    'btns' => array (
                        1 => array ( 'text' => 'Дивіться лендінг', 'type_link' => 'form', 'link' => 'https://www.youtube.com/watch?v=mnLV47KUDR4?t=47', 'type' => 'stroke_stretch', 'icon' => 'icon-09', ),
                    ),
                    'text_width' => '90',
                    'text_align' => 'center',
                    'font_size' => 'normal',
                    'top_separator' => 'S',
                    'bottom_separator' => 'S',
                ),
            ),
            3 => array (
                'visibility' => '1',
                'position' => '4',
                'component' => 'advantages',
                'content' => array (
                    'title' => 'Переваги',
                    'items_in_row' => '2',
                    'list' => array (
                        1 => array (
                            'image' => '/demo/62eaea76a9eed.svg',
                            'title' => 'Працює на framework Laravel',
                            'text' => '<p>Framework Laravel вважається одним із найсучасніших фреймоворків, які надають розробникам широкий спектр функцій для реалізації задач<br></p>',
                        ),
                        2 => array (
                            'image' => '/demo/62eaea76a9eed.svg',
                            'title' => 'Широкі можливості блоків',
                            'text' => '<p>Блоки можна додавати в необмеженій кількості на сторінку, міняти їх послідовність, міняти отступи між ними, скривати блоки , не видаляючи їх повністю та багато іншого</p>',
                        ),
                        3 => array (
                            'image' => '/demo/62eaea76a9eed.svg',
                            'title' => 'Максимальна швидкодія в роботі через адмін панель',
                            'text' => '<p>Засобами конструктора сторінки складаються граючи</p>',
                        ),
                        4 => array (
                            'image' => '/demo/62eaea76a9eed.svg',
                            'title' => 'Не вимагає знань програмування',
                            'text' => '<p>Додавайте текстово-графічний контент, контролюйте отступи між блоками, зображення для мобільних пристроїв та оцініть ще багато прибамбасів для створення гарної сторінки<br></p>',
                        ),
                    ),
                    'btns' => NULL,
                    'top_separator' => 'M',
                    'bottom_separator' => 'M',
                ),

            ),
            5 => array (
                'visibility' => '1',
                'position' => '5',
                'component' => 'simple-text',
                'content' => array (
                    'title' => 'Додаємо блоки та розміщуємо контент',
                    'description' => '<p>Конструктор складається з блоків, які адміністратор сайту додає в будь-якій кількості і має у будь-якій послідовності, може блоки міняти місцями між собою, тимчасово приховувати їх на сайті або видаляти назавжди. І впровадити цю функціональність можна в будь-який динамічний розділ.</p><p>Подивіться відео, як працює конструктор на прикладі одного з проектів</p>',
                    'btns' => NULL,
                    'text_width' => '60',
                    'text_align' => 'left',
                    'font_size' => 'normal',
                    'top_separator' => 'S',
                    'bottom_separator' => 'S',
                ),
            ),
            6 => array (
                'visibility' => '1',
                'position' => '6',
                'component' => 'video-and-text',
                'content' => array (
                    'title' => NULL,
                    'file' => 'https://www.youtube.com/watch?v=mnLV47KUDR4?t=47',
                    'image' => '/demo/Group 1085-min.jpg',
                    'btns' => NULL,
                    'top_separator' => 'NON',
                    'bottom_separator' => 'M',
                ),
            ),
            16 => array (
                'visibility' => '1',
                'position' => '7',
                'component' => 'numbers',
                'content' => array (
                    'title' => 'Ми пропонуємо:',
                    'items_in_row' => '3',
                    'list' => array (
                        1 => array (
                            'number' => '15',
                            'title' => 'типів блоків мінімальної зборки',
                            'text' => '<p>блоки, які необхідні для створення контенту текстових сторінок, наприклад, текстовий, із зображенням, галерея, переваги та ін.</p>',
                        ),
                        2 => array (
                            'number' => '25',
                            'title' => 'типів блоків стандартної зборки',
                            'text' => '<p>блоки, які дають повні можливості створення привабливого, корисного контенту</p>',
                        ),
                        3 => array (
                            'number' => '25+',
                            'title' => 'типів блоків з урахуванням унікальних',
                            'text' => '<p>якщо наші зборки не задовольняють потреби клієнта, то розробляємо додаткові блоки</p>',
                        ),
                    ),
                    'btns' => NULL,
                    'top_separator' => 'S',
                    'bottom_separator' => 'S',
                ),
            ),
            4 => array (
                'visibility' => '1',
                'position' => '8',
                'component' => 'stages',
                'content' => array (
                    'title' => 'Як це працює технічно?',
                    'list' => array (
                        1 => array (
                            'title' => 'Вибір розділів для імплементації конструктору',
                            'text' => '<p>Створюється розділ, наприклад, блог( статті), послуги, продукти або ін приймається рішення, що сторінки даного розділу будуть будуватися на long Read конструкторі.</p>',
                        ),
                        2 => array (
                            'title' => 'Затвердження наборів блоків для цих розділів',
                            'text' => '<p>Для кожного розділу розробляємо набір типів блоків. Наприклад, в розділі блог не потрібно багато типів блоків, а лише різновиди текстового, картинка+текст і, можливо, списки і цитата; а вже для послуг - весь цей набір + супутні послуги і товари, і ін.</p>',
                        ),
                        3 => array (
                            'title' => 'Реалізація',
                            'text' => '<p>Верстаємо всі варіації типів блоків, отримуємо від бекенда json, який розпарюється по сторінці і сторінка отримує вигляд згідно дизайну</p>',
                        ),
                    ),
                    'btns' => NULL,
                    'content_position' => 'right',
                    'font_size' => 'normal',
                    'top_separator' => 'M',
                    'bottom_separator' => 'M',
                ),
            ),
            14 => array (
                'visibility' => '1',
                'position' => '9',
                'component' => 'image-and-text',
                'content' => array (
                    'title' => NULL,
                    'image' => '/demo/Group1087-min.jpg',
                    'image_mob' => '/demo/Group1089-min.jpg',
                    'image_position' => 'left',
                    'column_width' => '7',
                    'image_height' => 'fix',
                    'font_size' => 'normal',
                    'top_separator' => 'M',
                    'bottom_separator' => 'M',
                    'description' => '<p>Наші конструктори вміють використовувати дані з інших розділів. Це означає, що ми можемо створювати товари і на сторінці розміщувати вибрані товари, і використовувати товарні категорії для зовсім іншого розділу, створюючи перелінковки.&nbsp;Ще приклади переваг з технічного боку-це блог, часті питання, відгуки, співробітники, послуги - такі типи блоків можуть бути створені індивідуально для проектів, щоб досягати додаткових перелінковок і продажів.</p>',
                    'btns' => NULL,
                ),
            ),
            10 => array (
                'visibility' => '1',
                'position' => '10',
                'component' => 'text-n-columns',
                'content' => array (
                    'title' => NULL,
                    'title_column_select' => '2',
                    'rows' => array (
                        1 => array (
                            0 => array (
                                'column_text' => 'Перша наша розробка конструктора була зроблена на CMS WordPress. Надихалися такими конструкторами як Elementary ... ми відразу побачили складність сприйняття клієнтами готових рішень цього конструктора( тобто елементарно або початкової версії нашого??), тому написали індивідуальне рішення для одного з наших проектів. У першому релізі було типів блоків разом з варіаціями. У першому релізі було 20 типів блоків разом з варіаціями.',
                            ),
                            1 => array (
                                'column_text' => 'Нам дуже сподобалося, як конструктор прийняли наші клієнти: як легко, без зайвих питань вони будували внутрішні сторінки. З входженням нових фахівців (маркетологи, seo-фахівці і контент-менеджери), вони відразу розуміли, як цим користуватися і робили відмінний контент, який з моменту запуску відразу давав результати. На сьогодні ми продовжуємо вдосконалювати нашу розробку на framework Laravel.',
                            ),
                        ),
                    ),
                    'btns' => NULL,
                    'font_size' => 'normal',
                    'top_separator' => 'M',
                    'bottom_separator' => 'M',
                ),
            ),
            15 => array (
                'visibility' => '1',
                'position' => '11',
                'component' => 'image-and-text',
                'content' => array (
                    'title' => NULL,
                    'image' => '/demo/Group1086-min.jpg',
                    'image_mob' => '/demo/Group1088-min.jpg',
                    'image_position' => 'right',
                    'column_width' => '3',
                    'image_height' => 'fix',
                    'font_size' => 'normal',
                    'top_separator' => 'M',
                    'bottom_separator' => 'M',
                    'description' => '<p>Функціонально у нас Всі типи блоків працюють однаково, тому всі оновлення, які будуть, можливо застосувати і для ваших проектів, а зовнішній вигляд (дизайн) кожного типу блоку розробляється індивідуально.</p>',
                    'btns' => NULL,
                ),
            ),
            9 => array (
                'visibility' => '1',
                'position' => '12',
                'component' => 'accordion',
                'content' => array (
                    'title' => 'Що ви отримуєте при використанні конструктору?',
                    'list' => array (
                        1 => array (
                            'title' => 'Оригінальне оформлення сторінок',
                            'text' => '<p>Конструктор дозволяє і довгі, і короткі текстові уривки оформити оригінально-виділяючи текст і списки різними стилями, через блоки конструктора, додаючи зображення, відео, анімації, тексти в довільному порядку.</p>',
                        ),
                        2 => array (
                            'title' => 'Збільшення усвідомленння аудиторії',
                            'text' => '<p>Гармонійне розміщення контенту, замість простирадла тексту, сприятиме привабленню потенційних користувачів сайту</p>',
                        ),
                        3 => array (
                            'title' => 'Збільшення показників часу та глибини перегляду сторінки',
                            'text' => '<p>Зацікавлений користувач не поспішить залишити сторінку, що сприяє збільшенню показників часу знаходження на сторінці<br></p>',
                        ),
                        4 => array (
                            'title' => 'Збільшення кількості конверсій',
                            'text' => '<p>Застосування конструктора для таких важливих сторінок, як Послуги, Продукти, сприяє збільшенню якості та кількості продажів <br></p>',
                        ),
                    ),
                    'btns' => NULL,
                    'content_position' => 'left',
                    'type' => 'numerical',
                    'top_separator' => 'M',
                    'bottom_separator' => 'M',
                ),
            ),
            11 => array (
                'visibility' => '1',
                'position' => '13',
                'component' => 'gallery',
                'content' => array (
                    'title' => 'Технології, які ми використали для створення конструктору',
                    'list' => array (
                        1 => array (
                            'image' => '/demo/Group200-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        2 => array (
                            'image' => '/demo/Group199-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        3 => array (
                            'image' => '/demo/Group201-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        4 => array (
                            'image' => '/demo/Group202-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        5 => array (
                            'image' => '/demo/Group203-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        6 => array (
                            'image' => '/demo/Group218-1-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        7 => array (
                            'image' => '/demo/Group214-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        8 => array (
                            'image' => '/demo/Group213-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        9 => array (
                            'image' => '/demo/Group211-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        10 => array (
                            'image' => '/demo/Group205-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                        11 => array (
                            'image' => '/demo/Group2559-min.jpg',
                            'image_title' => NULL,
                            'sort' => NULL,
                        ),
                    ),
                    'btns' => NULL,
                    'size_fix' => 'auto_height',
                    'align' => 'center',
                    'top_separator' => 'M',
                    'bottom_separator' => 'M',
                ),
            ),
            17 => array (
                'visibility' => '1',
                'position' => '14',
                'component' => 'blocks',
                'content' => array (
                    'title' => 'Ще цікаве',
                    'list' => array (
                        1 => array (
                            'image' => '/demo/komanda.jpg',
                            'title' => 'Наша команда',
                            'text' => 'Познайомтесь з нашою командою, ми вам сподобаємось не лише нашими розробками',
                            'btn_name' => 'Читати',
                            'interlink_type' => 'Pages',
                            'interlink_val' => array (
                                'Pages' => '1',
                                'arbitrary' => NULL,
                            ),
                        ),
                        2 => array (
                            'image' => '/demo/novosti.jpg',
                            'title' => 'Блог',
                            'text' => '<p>Цікаві статті із сфери IT</p>',
                            'btn_name' => 'Читати',
                            'interlink_type' => 'Pages',
                            'interlink_val' => array (
                                'Pages' => '1',
                                'arbitrary' => NULL,
                            ),
                        ),
                        4 => array (
                            'image' => '/demo/razrabotka.jpg',
                            'title' => 'Наша CMS',
                            'text' => NULL,
                            'btn_name' => 'Читати',
                            'interlink_type' => 'Pages',
                            'interlink_val' => array (
                                'Pages' => '1',
                                'arbitrary' => NULL,
                            ),
                        ),
                        5 => array (
                            'image' => '/demo/telefon.jpg',
                            'title' => 'Контакти',
                            'text' => '<p>Ми відповімо на ваші запитання та допоможемо з вибором технологій</p>',
                            'btn_name' => 'Читати',
                            'interlink_type' => 'Pages',
                            'interlink_val' => array (
                                'Pages' => '1',
                                'arbitrary' => NULL,
                            ),
                        ),
                    ),
                    'btns' => NULL,
                    'title_column_select' => '4',
                    'card_btn_style_type' => 'text',
                    'card_btn_style_icon' => 'icon-arrow-right-2',
                    'top_separator' => 'S',
                    'bottom_separator' => 'S',
                ),
            ),
            13 => array (
                'visibility' => '0',
                'position' => '15',
                'component' => 'simple-text',
                'content' => array (
                    'title' => NULL,
                    'description' => '<p>* Ця сторінка зроблена на конструкторі :)</p>',
                    'btns' => NULL,
                    'text_width' => '90',
                    'text_align' => 'center',
                    'font_size' => 'small',
                    'top_separator' => 'S',
                    'bottom_separator' => 'S',
                ),
            ),
        );

        //ru
        $data['ru'] = [
            'title' => 'Главная',
            'meta_title' => 'Главная',
            'meta_description' => 'Главная',
            'status_lang' => 1,
        ];

        $constructor['ru'] = array (
            1 => array (
                'visibility' => '1',
                'position' => '1',
                'component' => 'widget',
                'content' => array ( 'widget' => NULL, ),
            ),
        );

        //en
        $data['en'] = [
            'title' => 'Home',
            'meta_title' => 'Home',
            'meta_description' => 'Home',
            'status_lang' => 1,
        ];

        $constructor['en'] = array (
            1 => array (
                'visibility' => '1',
                'position' => '1',
                'component' => 'widget',
                'content' => array ( 'widget' => NULL, ),
            ),
        );


        foreach (config('translatable.locales') as $lang => $item) {
            $trans = $page->translateOrNew($lang);
            $trans->fill($data[$lang]);
            $trans->fillConstructorable($constructor[$lang]);

        }

        $this->adapter->renderConstructorHTML($page);
        $page->save();
    }

    private function contacts()
    {

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

        $constructor['uk'] = array (
            1 => array (
                'visibility' => '1',
                'position' => '1',
                'component' => 'widget',
                'content' => array ( 'widget' => NULL, ),
            ),
        );

        //ru
        $data['ru'] = [
            'title' => 'Контакты',
            'meta_title' => 'Контакты',
            'meta_description' => 'Контакты',
            'status_lang' => 1,
        ];

        $constructor['ru'] = array (
            1 => array (
                'visibility' => '1',
                'position' => '1',
                'component' => 'widget',
                'content' => array ( 'widget' => NULL, ),
            ),
        );

        //en
        $data['en'] = [
            'title' => 'Contacts',
            'meta_title' => 'Contacts',
            'meta_description' => 'Contacts',
            'status_lang' => 1,
        ];

        $constructor['en'] = array (
            1 => array (
                'visibility' => '1',
                'position' => '1',
                'component' => 'widget',
                'content' => array ( 'widget' => NULL, ),
            ),
        );

        foreach (config('translatable.locales') as $lang => $item) {
            $trans = $page->translateOrNew($lang);
            $trans->fill($data[$lang]);
            $trans->fillConstructorable($constructor[$lang]);
        }


        $page->save();
        $this->adapter->renderConstructorHTML($page);


    }

    private function about()
    {

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

        $constructor['uk'] = [
            "entity_name" => "App\Models\Translations\PageTranslation",
            "entity_id" => "7",
            "constructor" => [
                5 => [
                    "visibility" => "1",
                    "position" => "1",
                    "component" => "widget",
                    "content" => [
                        "widget" => null,
                    ],
                ],
                1 => [
                    "visibility" => "1",
                    "position" => "2",
                    "component" => "simple-text",
                    "content" => [
                        "title" => null,
                        "description" => "<p>Ми створюємо круті проєкти для тих, хто цінує свій час та прагне найкращих рішень. Функціональне має бути гарним, а гарне - функціональним.</p><p>Ви готові отримати найкраще за розумну та аргументовану ціну?</p>",
                        "btns" => [
                            1 => [
                            "text" => "Давайте працювати разом",
                            "type_link" => "link",
                            "link" => "/contacts",
                            "type" => "fill",
                            "icon" => "icon-13",
                            ],
                        ],
                        "text_width" => "90",
                        "text_align" => "center",
                        "font_size" => "normal",
                        "top_separator" => "S",
                        "bottom_separator" => "S",
                    ],
                ],
                2 => [
                    "visibility" => "1",
                    "position" => "3",
                    "component" => "accordion",
                    "content" => [
                        "title" => "Наші послуги",
                        "list" => [
                            1 => [
                                "title" => "UI / UX дизайн",
                                "text" => "<p>В дизайні ми керуємось нюансами та користувацьким досвідом роботи в інтернеті. Дизайн повинен бути обдуманим! Тому наші продукти високо оцінює професійне ком’юнеті, спробуйте і Ви.</p>",
                            ],
                            2 => [
                                "title" => "Front-end розробка",
                                "text" => "<p>Frontend розробник — це фахівець, що займається створенням структури гіпертекстового документа, в основі якого лежить HTML-розмітка + CSS-стилізація + JS-інтерактиви. Простими словами, він втілює в реальність дизайнерські проєкти, переводить зображення в браузерний формат.<br></p>",
                            ],
                            3 => [
                                "title" => "Back-end розробка",
                                "text" => "<p>Back-end розробка — це створення серверної частини веб-продукту та реалізація його функціональностей. Бекенд програмування складається з розробки інтерфейсів взаємодії, бази даних, серверів.<br></p>",
                            ],
                            4 => [
                                "title" => "Технічна підтримка",
                                "text" => "<p>Технічна підтримка від OwlWeb це сукупність послуг для підтримки працездатності веб-сайту, нарощування його потенційних можливостей, виправлення помилок, які виникають в ході доопрацювань проєкту.</p><p><br></p>",
                            ],
                        ],
                        "btns" => null,
                        "content_position" => "right",
                        "type" => "numerical",
                        "top_separator" => "S",
                        "bottom_separator" => "S",
                    ],
                ],
                4 => [
                    "visibility" => "1",
                    "position" => "4",
                    "component" => "gallery",
                    "content" => [
                        "title" => "Наші співробітники",
                        "list" => [
                            1 => [
                                "image" => "/demo/photo_2021-10-31_20-40-33-min.jpg",
                                "image_title" => "project manager",
                                "sort" => "0",
                            ],
                            2 => [
                                "image" => "/demo/photo_2019-02-18_15-21-40-min.jpg",
                                "image_title" => "designer",
                                "sort" => "1",
                            ],
                            3 => [
                                "image" => "/demo/photo_2020-07-16_11-41-24-min.jpg",
                                "image_title" => "designer",
                                "sort" => "2",
                            ],
                            4 => [
                                "image" => "/demo/photo_2020-05-18_19-16-08-min.jpg",
                                "image_title" => "backend specialist",
                                "sort" => "3",
                            ],
                            5 => [
                                "image" => "/demo/Screenshot_8-min.jpg",
                                "image_title" => "backend specialist",
                                "sort" => "4",
                            ],
                            6 => [
                                "image" => "/demo/photo_2019-12-05_00-53-01-min.jpg",
                                "image_title" => "frontend specialist",
                                "sort" => "5",
                            ],
                            7 => [
                                "image" => "/demo/Screenshot_9.jpg",
                                "image_title" => "frontend specialist",
                                "sort" => "6",
                            ],
                            8 => [
                                "image" => "/demo/photo_2021-10-22_17-20-46-min.jpg",
                                "image_title" => "content/account manager",
                                "sort" => "7",
                            ],
                            9 => [
                                "image" => "/demo/photo_2022-03-15_15-29-20-min.jpg",
                                "image_title" => "content/account manager",
                                "sort" => "8",
                            ],
                            11 => [
                                "image" => "/demo/photo_2021-12-24_00-57-43-min.jpg",
                                "image_title" => "content/account manager",
                                "sort" => "9",
                            ],
                            10 => [
                                "image" => "/demo/Screenshot_7-min.jpg",
                                "image_title" => "SEO specialist",
                                "sort" => "10",
                            ],
                        ],
                    "btns" => null,
                    "size_fix" => "auto_height",
                    "align" => "top",
                    "top_separator" => "S",
                    "bottom_separator" => "S",
                    ],
                ],
            ],
        ];

        //ru
        $data['ru'] = [
            'title' => 'О нас',
            'meta_title' => 'О нас',
            'meta_description' => 'О нас',
            'status_lang' => 1,
        ];

        $constructor['ru'] = array (
            1 => array (
                'visibility' => '1',
                'position' => '1',
                'component' => 'widget',
                'content' => array ( 'widget' => NULL, ),
            ),
        );

        //en
        $data['en'] = [
            'title' => 'About us',
            'meta_title' => 'About us',
            'meta_description' => 'About us',
            'status_lang' => 1,
        ];

        $constructor['en'] = array (
            1 => array (
                'visibility' => '1',
                'position' => '1',
                'component' => 'widget',
                'content' => array ( 'widget' => NULL, ),
            ),
        );

        foreach (config('translatable.locales') as $lang => $item) {
            $trans = $page->translateOrNew($lang);
            $trans->fill($data[$lang]);
            $trans->fillConstructorable($constructor[$lang]);
        }


        $page->save();
        $this->adapter->renderConstructorHTML($page);


    }
}
