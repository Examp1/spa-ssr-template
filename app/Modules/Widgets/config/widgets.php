<?php

defined('WIDGET_GROUP_PAGE') or define('WIDGET_GROUP_PAGE', 1);
defined('WIDGET_GROUP_LANDING') or define('WIDGET_GROUP_LANDING', 2);
defined('WIDGET_GROUP_ARTICLE') or define('WIDGET_GROUP_ARTICLE', 3);

return [

    /*
    |------------------------------------------------------------------
    | View layout component
    |------------------------------------------------------------------
    */
    //    'view-layout' => 'admin::layout',
    'view-layout'  => 'admin::layout',

    /*
    |------------------------------------------------------------------
    | View layout title
    |------------------------------------------------------------------
    */
    'layout-title' => 'widgets::strings.layout.title',


    'widgets'           => [
        'first-screen'         => \App\Modules\Widgets\Collections\FirstScreen\FirstScreenWidget::class,
        'first-screen-slider'  => \App\Modules\Widgets\Collections\FirstScreen\SliderWidget::class,
        'ticker'               => \App\Modules\Widgets\Collections\Ticker\TickerWidget::class,
        'simple-text'          => \App\Modules\Widgets\Collections\SimpleText\SimpleTextWidget::class,
        'image-and-text'       => \App\Modules\Widgets\Collections\ImageAndText\ImageAndTextWidget::class,
        'full-with-image'      => \App\Modules\Widgets\Collections\FullWithImage\FullWithImageWidget::class,
        'text-n-columns'       => \App\Modules\Widgets\Collections\TextNColumns\TextNColumnsWidget::class,
        'tables'               => \App\Modules\Widgets\Collections\Tables\TablesWidget::class,
        'accordion'            => \App\Modules\Widgets\Collections\Accordion\AccordionWidget::class,
        'gallery'              => \App\Modules\Widgets\Collections\Gallery\GalleryWidget::class,
        'video-and-text'       => \App\Modules\Widgets\Collections\VideoAndText\VideoAndTextWidget::class,
        'blocks'               => \App\Modules\Widgets\Collections\Blocks\BlocksWidget::class,
        'see-also'             => \App\Modules\Widgets\Collections\SeeAlso\SeeAlsoWidget::class,
        'advantages'           => \App\Modules\Widgets\Collections\Advantages\AdvantagesWidget::class,
        'numbers'              => \App\Modules\Widgets\Collections\Numbers\NumbersWidget::class,
        'quote'                => \App\Modules\Widgets\Collections\Quote\QuoteWidget::class,
        'contacts'             => \App\Modules\Widgets\Collections\Contacts\ContactsWidget::class,
        'contacts-tabs'        => \App\Modules\Widgets\Collections\ContactsTabs\ContactsTabsWidget::class,
        'categories-w'         => \App\Modules\Widgets\Collections\Catalog\CategoriesWidget::class,
        'products-w'           => \App\Modules\Widgets\Collections\Catalog\ProductsWidget::class,
        'product-slider'       => \App\Modules\Widgets\Collections\Catalog\ProductSliderWidget::class,
        'image-video-and-text' => \App\Modules\Widgets\Collections\ImageVideoAndText\ImageVideoAndTextWidget::class,
        'reviews'              => \App\Modules\Widgets\Collections\Reviews\ReviewsWidget::class,
    ],

    /*
    |------------------------------------------------------------------
    | Groups for widget
    |------------------------------------------------------------------
    */
    'groups'            => [
        WIDGET_GROUP_PAGE    => 'Сторінка',
        WIDGET_GROUP_LANDING => 'Лендінг',
//        WIDGET_GROUP_ARTICLE => 'Публікація блога',
    ],

    /*
    |------------------------------------------------------------------
    | Permissions for manipulation widgets
    |------------------------------------------------------------------
    */
    'permissions'       => [
        'widgets.view'   => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
        'widgets.create' => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
        'widgets.update' => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
        'widgets.delete' => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
    ],

    /*
    |------------------------------------------------------------------
    | Middleware for settings
    |------------------------------------------------------------------
    */
    'middleware'        => ['web', 'auth', 'verified'],

    /*
    |------------------------------------------------------------------
    | Uri Route prefix
    |------------------------------------------------------------------
    */
    'uri_prefix'        => 'admin',

    /*
    |------------------------------------------------------------------
    | Route name prefix
    |------------------------------------------------------------------
    */
    'route_name_prefix' => 'admin.',

    /*
    |------------------------------------------------------------------
    | Request lang key
    |------------------------------------------------------------------
    */
    'request_lang_key'  => 'lang',

];
