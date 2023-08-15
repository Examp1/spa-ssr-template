<?php

namespace App\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Sitemap
{
    private $langs;
    private $defaultLangCode;

    public function __construct()
    {
        $this->langs = config('translatable.locales');
        $this->defaultLangCode = config('translatable.locale');
    }

    public function generate()
    {
        Log::info('sitemap generate start',[]);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'.PHP_EOL;

        foreach (config('sitemap.entities') as $item){
            $model = $item['model'];
            $xml .= $model::sitemapGenerate($item['changefreq'], $item['priority']);
        }

        $xml .= '</urlset>';

        try {
            file_put_contents(public_path('sitemap.xml'), $xml);
        } catch (\Throwable $e) {
            Log::error('Cant save sitemap file', $e);
        }

        try {
            chmod(public_path('sitemap.xml'), 0665);
        } catch (\Throwable $e) {
            Log::error('Cant set permissions on sitemap file', $e);
        }

        Log::info('sitemap generated', []);
    }

    private function isValidUri($uri, $isNews = false, $item = null, $lang = null)
    {
        if ($isNews) {
            return $item->hasLang($lang);
        }

        return true;
    }

    public function row($loc, $changefreq, $priority, $isNews = false, $item = null)
    {
        $url = url($loc);

        if (!$this->isValidUri($url, $isNews, $item, $this->defaultLangCode)) return '';

        $res = '';

        foreach ($this->langs as $lang => $lang_name) {
            if ($this->defaultLangCode == $lang) {
                if ($url == '//') {
                    $url = env('APP_URL');
                    $priority = '1.0';
                }

                if ($url == env('APP_URL')) {
                    $priority = '1.0';
                }

                $locLang = $url;
            } else {
                $locLang = url(str_replace(trim(config('app.url'), '/'), config('app.url') . $lang, $url));
            }

            if ($this->isValidUri($locLang, $isNews, $item, $lang)) {

                $res .= '<url><loc>' . $locLang . '</loc>' . PHP_EOL;

                if ($item) {
                    $res .= '<lastmod>' . $item->updated_at->format('Y-m-d') . '</lastmod>' . PHP_EOL;
                } else {
                    $res .= '<lastmod>' . Carbon::now()->format('Y-m-d') . '</lastmod>' . PHP_EOL;
                }

                $res .= '<changefreq>' . $changefreq . '</changefreq><priority>' . $priority . '</priority></url>' . PHP_EOL;
            };
        }

        return $res;
    }
}
