<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models for sitemap generate
    |
    | changefreq param:
    | always, hourly, daily, weekly, monthly, yearly, never
    |
    |--------------------------------------------------------------------------
    */
    'entities' => [
        'Pages'          => [
            'model'      => \App\Models\Pages::class,
            'changefreq' => 'daily',
            'priority'   => '0.9'
        ],
        'BlogArticles'   => [
            'model' => \App\Models\BlogArticles::class,
            'changefreq' => 'daily',
            'priority'   => '0.8'
        ],
        'BlogCategories' => [
            'model' => \App\Models\BlogCategories::class,
            'changefreq' => 'monthly',
            'priority'   => '0.8'
        ]
    ],

    /*
   |--------------------------------------------------------------------------
   | schedule sitemap generate dailyAt
   |--------------------------------------------------------------------------
   */
    'schedule_generate_daily_at' => '12:00'
];
