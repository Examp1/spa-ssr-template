<?php

return [
    /*
     * Models for selecting menu items
     */
    'entities' => [
        'Pages'           => \App\Models\Pages::class,
        'BlogArticles'    => \App\Models\BlogArticles::class,
        'BlogCategories'  => \App\Models\BlogCategories::class,
        'Landing'         => \App\Models\Landing::class,
        'Product'         => \Owlwebdev\Ecom\Models\Product::class,
        'ProductCategory' => \Owlwebdev\Ecom\Models\Category::class
    ],
];
